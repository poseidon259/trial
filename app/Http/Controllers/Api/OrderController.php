<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\GetListOrderRequest;
use App\Services\OrderService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * @var OrderService
     */
    private $orderService;

    public function __construct(
        OrderService $orderService
    ) {
        $this->orderService = $orderService;
    }

    public function create(CreateOrderRequest $request) {
        DB::beginTransaction();
        try {
            $order = $this->orderService->create($request);
            DB::commit();
            return $order;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function delete($id) {
        DB::beginTransaction();
        try {
            $order = $this->orderService->delete($id);
            DB::commit();
            return $order;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function show($id) {
        try {
            return $this->orderService->show($id);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function list(GetListOrderRequest $request) {
        try {
            return $this->orderService->getList($request);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }
}

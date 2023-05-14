<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\GetListOrderRequest;
use App\Http\Requests\ListOrderHistoryRequest;
use App\Http\Requests\ReturnUrlVNPayRequest;
use App\Http\Requests\UpdateStatusOrderRequest;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    )
    {
        $this->orderService = $orderService;
    }

    public function create(CreateOrderRequest $request)
    {
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

    public function delete($id)
    {
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

    public function show($id)
    {
        try {
            return $this->orderService->show($id);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function list(GetListOrderRequest $request)
    {
        try {
            return $this->orderService->getList($request);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function createOrderByUser(CreateOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $order = $this->orderService->createOrderByUser($request, $user);
            DB::commit();
            return $order;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function paymentVNPay($orderId)
    {
        try {
            return $this->orderService->paymentVNPay($orderId);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function checkPaymentVnpay(ReturnUrlVNPayRequest $request)
    {
        try {
            return $this->orderService->checkPaymentVnpay($request);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function ipnVNPay(ReturnUrlVNPayRequest $request)
    {
        try {
            return $this->orderService->ipnVNPay($request);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function detailOrderPublic($id)
    {
        try {
            $user = Auth::user();
            return $this->orderService->detailOrderPublic($id, $user);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function listOrderHistory(ListOrderHistoryRequest $request)
    {
        try {
            $user = Auth::user();
            return $this->orderService->listOrderHistory($request, $user);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function updateStatusOrder(UpdateStatusOrderRequest $request, $id)
    {
        try {
            return $this->orderService->updateStatusOrder($request, $id);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }
}

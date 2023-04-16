<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateStoreRequest;
use App\Http\Requests\GetListStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Services\StoreService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreController extends Controller
{
    /**
     * @var StoreService
     */
    private $storeService;

    public function __construct(
        StoreService $storeService
    )
    {
        $this->storeService = $storeService;
    }

    public function create(CreateStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $store = $this->storeService->create($request);
            DB::commit();
            return $store;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function update(UpdateStoreRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $store = $this->storeService->update($request, $id);
            DB::commit();
            return $store;
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
            $store = $this->storeService->delete($id);
            DB::commit();
            return $store;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function show($id)
    {
        try {
            $store = $this->storeService->show($id);
            return $store;
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function list(GetListStoreRequest $request)
    {
        try {
            $stores = $this->storeService->list($request);
            return $stores;
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function getStoreHomepage()
    {
        try {
            return $this->storeService->getStoreHomepage();
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function detailStorePublic($id)
    {
        try {
            return $this->storeService->detailStorePublic($id);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }
}

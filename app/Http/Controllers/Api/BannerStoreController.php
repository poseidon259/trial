<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBannerStoreRequest;
use App\Services\BannerStoreService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BannerStoreController extends Controller
{
    /**
     * @var BannerStoreService
     */
    private $bannerStoreService;

    public function __construct(
        BannerStoreService $bannerStoreService,
    )
    {
        $this->bannerStoreService = $bannerStoreService;
    }

    public function update(CreateBannerStoreRequest $request, $storeId)
    {
        DB::beginTransaction();
        try {
            $bannerStore = $this->bannerStoreService->update($request, $storeId);
            DB::commit();
            return $bannerStore;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function list($storeId)
    {
        try {
            return $this->bannerStoreService->list($storeId);
        } catch (\Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function getListBannerStorePublic($storeId)
    {
        try {
            return $this->bannerStoreService->getListBannerStorePublic($storeId);
        } catch (\Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }
}

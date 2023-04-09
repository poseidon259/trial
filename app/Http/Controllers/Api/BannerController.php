<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBannerRequest;
use App\Services\BannerService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BannerController extends Controller
{
    private $bannerService;

    public function __construct(
        BannerService $bannerService,
    ) {
        $this->bannerService = $bannerService;
    }

    public function update(CreateBannerRequest $request)
    {
        DB::beginTransaction();
        try {
            $banner = $this->bannerService->update($request);
            DB::commit();
            return $banner;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function list()
    {
        try {
            return $this->bannerService->list();
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function getListBannerPublic()
    {
        try {
            return $this->bannerService->getListBannerPublic();
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }
}

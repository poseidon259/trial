<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductFavoriteRequest;
use App\Services\ProductFavoriteService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductFavoriteController extends Controller
{
    /**
     * @var ProductFavoriteService
     */
    private $productFavoriteService;

    public function __construct(
        ProductFavoriteService $productFavoriteService
    ) {
        $this->productFavoriteService = $productFavoriteService;
    }

    public function create(CreateProductFavoriteRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $response = $this->productFavoriteService->create($request, $user);
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $response = $this->productFavoriteService->delete($id);
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function list()
    {
        try {
            $user = Auth::user();
            $response = $this->productFavoriteService->list($user);
            return $response;
        } catch (\Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }
}

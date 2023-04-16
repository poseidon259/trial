<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\DetailProductPublicRequest;
use App\Http\Requests\GetListProductByStoreRequest;
use App\Http\Requests\GetListProductHomepageRequest;
use App\Http\Requests\GetListProductRequest;
use App\Http\Requests\GetProductsByCategoryRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    private ProductService $productService;

    public function __construct(
        ProductService $productService
    )
    {
        $this->productService = $productService;
    }

    public function create(CreateProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $result = $this->productService->create($request, $user);
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function update(UpdateProductRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $result = $this->productService->update($request, $user, $id);
            DB::commit();
            return $result;
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
            $result = $this->productService->delete($id);
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function list(GetListProductRequest $request)
    {
        try {
            return $this->productService->list($request);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function show($id)
    {
        try {
            return $this->productService->show($id);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function getListProductPublic(GetListProductHomepageRequest $request)
    {
        try {
            return $this->productService->getListProductPublic($request);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function detailProductPublic(DetailProductPublicRequest $request, $id)
    {
        try {
            return $this->productService->detailProductPublic($request, $id);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function getListProductByCategory(GetProductsByCategoryRequest $request, $categoryId)
    {
        try {
            return $this->productService->getListProductByCategory($request, $categoryId);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function getListProductByStore(GetListProductByStoreRequest $request, $storeId)
    {
        try {
            return $this->productService->getListProductByStore($request, $storeId);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }
}

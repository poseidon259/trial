<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryChildRequest;
use App\Http\Requests\UpdateCategoryChildRequest;
use App\Services\CategoryChildService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryChildController extends Controller
{
        /**
     * @var CategoryChildService
     */
    private $categoryChildService;

    public function __construct(
        CategoryChildService $categoryChildService
    ) {
        $this->categoryChildService = $categoryChildService;
    }

    public function create(CreateCategoryChildRequest $request, $categoryId)
    {
        DB::beginTransaction();
        try {
            $categoryChild = $this->categoryChildService->create($request, $categoryId);
            DB::commit();
            return $categoryChild;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function update(UpdateCategoryChildRequest $request, $categoryId, $id)
    {
        DB::beginTransaction();
        try {
            $category = $this->categoryChildService->update($request, $categoryId, $id);
            DB::commit();
            return $category;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function delete($categoryId, $id)
    {
        DB::beginTransaction();
        try {
            $category = $this->categoryChildService->delete($categoryId, $id);
            DB::commit();
            return $category;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

}

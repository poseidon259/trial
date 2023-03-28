<?php

namespace App\Services;

use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;

class CategoryService
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepostiryInterface;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepositoryInterface;

    public function __construct(
        CategoryRepositoryInterface $categoryRepostiryInterface,
        ProductRepositoryInterface $productRepositoryInterface
    ) {
        $this->categoryRepostiryInterface = $categoryRepostiryInterface;
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    public function create($request)
    {
        $checkExists = $this->categoryRepostiryInterface->findOne('name', $request->name);

        if ($checkExists) {
            return _error(null, __('messages.category_exists'), HTTP_BAD_REQUEST);
        }

        $params = [
            'name' => $request->name,
        ];

        $category = $this->categoryRepostiryInterface->create($params);

        if (!$category) {
            return _error(null, __('messages.create_error'), HTTP_BAD_REQUEST);
        }

        return _success(null, __('messages.create_success'), HTTP_SUCCESS);
    }

    public function update($request, $id)
    {
        $old = $this->categoryRepostiryInterface->find($id);

        if (!$old) {
            return _error(null, __('messages.category_not_found'), HTTP_BAD_REQUEST);
        }

        $checkExists = $this->categoryRepostiryInterface->checkExists('name', $request->name, $id);

        if ($checkExists) {
            return _error(null, __('messages.category_exists'), HTTP_BAD_REQUEST);
        }

        $params = [
            'name' => $request->name,
        ];

        $category = $this->categoryRepostiryInterface->update($id, $params);

        if (!$category) {
            return _error(null, __('messages.update_error'), HTTP_BAD_REQUEST);
        }

        return _success(null, __('messages.update_success'), HTTP_SUCCESS);
    }

    public function delete($id)
    {
        $checkExists = $this->categoryRepostiryInterface->find($id);

        if (!$checkExists) {
            return _error(null, __('messages.category_not_found'), HTTP_BAD_REQUEST);
        }

        $products = $this->productRepositoryInterface->countByCategory($id);

        if ($products > 0) {
            return _error(null, __('messages.category_has_product_cant_delete'), HTTP_BAD_REQUEST);
        }

        $category = $this->categoryRepostiryInterface->delete($id);

        if (!$category) {
            return _error(null, __('messages.delete_error'), HTTP_BAD_REQUEST);
        }

        return _success(null, __('messages.delete_success'), HTTP_SUCCESS);
    }

    public function list($request)
    {
        $limit = $request->limit ?? LIMIT;
        $page = $request->page ?? PAGE;

        $categories = $this->categoryRepostiryInterface->getListCategory($request)->paginate($limit, $page);

        return [
            'categories' => $categories->items(),
            'total' => $categories->total(),
            'current_page' => $categories->currentPage(),
            'last_page' => $categories->lastPage(),
            'per_page' => $categories->perPage(),
        ];
    }

    public function show($id)
    {
        $category = $this->categoryRepostiryInterface->find($id);

        if (!$category) {
            return _error(null, __('messages.category_not_found'), HTTP_BAD_REQUEST);
        }

        $category = $this->categoryRepostiryInterface->detail($id);
        return _success($category, __('messages.success'), HTTP_SUCCESS);
    }
}

<?php

namespace App\Services;

use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\CategoryChild\CategoryChildRepositoryInterface;

class CategoryChildService
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepostiryInterface;

    /**
     * @var CategoryChildRepositoryInterface
     */
    private $categoryChildRepostiryInterface;

    public function __construct(
        CategoryRepositoryInterface $categoryRepostiryInterface,
        CategoryChildRepositoryInterface $categoryChildRepostiryInterface
    ) {
        $this->categoryRepostiryInterface = $categoryRepostiryInterface;
        $this->categoryChildRepostiryInterface = $categoryChildRepostiryInterface;
    }

    public function create($request, $categoryId)
    {
        if (isset($request->names)) {
            $category = $this->categoryRepostiryInterface->find($categoryId);

            if (!$category) {
                return _error(null, __('messages.category_not_found'), HTTP_BAD_REQUEST);
            }
    
            $checkExists = $this->categoryChildRepostiryInterface->findNames($request->names, $categoryId);
            if ($checkExists > 0) {
                return _error(null, __('messages.duplicate_category_name'), HTTP_BAD_REQUEST);
            }
    
            $data = [];
            if (isset($request->names)) {
                foreach ($request->names as $name) {
                    $data[] = [
                        'category_id' => $categoryId,
                        'name' => $name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
    
            $this->categoryChildRepostiryInterface->insert($data);
            return _success(null, __('messages.create_success'), HTTP_SUCCESS);
        }

        return _error(null, __('messages.create_error'), HTTP_BAD_REQUEST);
    }

    public function update($request, $categoryId, $id)
    {
        $category = $this->categoryRepostiryInterface->find($categoryId);

        if (!$category) {
            return _error(null, __('messages.category_not_found'), HTTP_BAD_REQUEST);
        }

        $checkExistsName = $this->categoryChildRepostiryInterface->findOne('name', $request->name, $categoryId, $id );

        if ($checkExistsName) {
            return _error(null, __('messages.duplicate_category_name'), HTTP_BAD_REQUEST);
        }

        $params = [
            'name' => $request->name,
        ];

        $categoryChild = $this->categoryChildRepostiryInterface->update($id, $params);

        if (!$categoryChild) {
            return _error(null, __('messages.update_error'), HTTP_BAD_REQUEST);
        }

        return _success(null, __('messages.update_success'), HTTP_SUCCESS);
    }

    public function delete($categoryId, $id)
    {
        $checkExists = $this->categoryRepostiryInterface->find($categoryId);

        if (!$checkExists) {
            return _error(null, __('messages.category_not_found'), HTTP_BAD_REQUEST);
        }

        $checkExists = $this->categoryChildRepostiryInterface->find($id);

        if (!$checkExists) {
            return _error(null, __('messages.category_not_found'), HTTP_BAD_REQUEST);
        }

        $categoryChild = $this->categoryChildRepostiryInterface->delete($id);

        if (!$categoryChild) {
            return _error(null, __('messages.delete_error'), HTTP_BAD_REQUEST);
        }

        return _success(null, __('messages.delete_success'), HTTP_SUCCESS);
    }
}

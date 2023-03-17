<?php

namespace App\Services;

use App\Repositories\Category\CategoryRepositoryInterface;

class CategoryService
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepostiryInterface;

    public function __construct(
        CategoryRepositoryInterface $categoryRepostiryInterface
    )
    {
        $this->categoryRepostiryInterface = $categoryRepostiryInterface;
    }

    public function create($request)
    {
        $params = [
            'name' => $request->name,
        ];

        if ($request->parent_id) {
            $parent = $this->categoryRepostiryInterface->find($request->parent_id);

            if (!$parent) {
                return _error(null, 'messages.category_not_found', HTTP_BAD_REQUEST);
            }

            $params['parent_id'] = $parent->id;
        }

        $category = $this->categoryRepostiryInterface->create($params);

        if (!$category) {
            return _error(null, 'messages.create_error', HTTP_BAD_REQUEST);
        }

        return _success($category, 'messages.create_success', HTTP_SUCCESS);
    }

    public function update($request, $id)
    {
        $old = $this->categoryRepostiryInterface->find($id);

        if (!$old) {
            return _error(null, 'messages.category_not_found', HTTP_BAD_REQUEST);
        }

        $params = [
            'name' => $request->name,
        ];

        if ($request->parent_id) {
            $parent = $this->categoryRepostiryInterface->find($request->parent_id);

            if (!$parent) {
                return _error(null, 'messages.category_not_found', HTTP_BAD_REQUEST);
            }

            $params['parent_id'] = $parent->id;
        }

        $category = $this->categoryRepostiryInterface->update($id, $params);

        if (!$category) {
            return _error(null, 'messages.create_error', HTTP_BAD_REQUEST);
        }

        return _success($category, 'messages.create_success', HTTP_SUCCESS);
    }

    public function delete($id)
    {
        $checkExists = $this->categoryRepostiryInterface->find($id);

        if (!$checkExists) {
            return _error(null, 'messages.category_not_found', HTTP_BAD_REQUEST);
        }

        $category = $this->categoryRepostiryInterface->delete($id);

        if (!$category) {
            return _error(null, 'messages.delete_error', HTTP_BAD_REQUEST);
        }

        return _success($category, 'messages.delete_success', HTTP_SUCCESS);
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

}
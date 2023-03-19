<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\Base\BaseRepository;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Category::class;
    }

    /**
     * Check exists
     *
     * @param $key
     * @param $value
     * @param $userId
     * @return mixed
     */
    public function checkExists($key, $value, $userId) {
        return $this->_model->where($key, $value)->where('users.id', '!=', $userId)->first();
    }

    /**
     * Find one
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public function findOne($key, $value) {
        return $this->_model->where($key, $value)->first();
    }


    /**
     * Get list category
     *
     * @param $request
     * @return mixed
     */
    public function getListCategory($request) {
        $query = $this->_model->select('*');

        return $query->orderBy('categories.id', 'desc');
    }

    /**
     * Detail
     *
     * @param $id
     * @return mixed
     */
    public function detail($id) {
        return $this->_model
                    ->select('categories.id', 'categories.name')
                    ->with(['children' => function ($query) {
                        return $query->select('categories.id', 'categories.name', 'categories.parent_id');
                    }])
                    ->where('categories.id', $id)
                    ->first();
    }

}
<?php

namespace App\Repositories\CategoryChild;

use App\Models\CategoryChild;
use App\Repositories\Base\BaseRepository;
use App\Repositories\CategoryChild\CategoryChildRepositoryInterface;

class CategoryChildRepository extends BaseRepository implements CategoryChildRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return CategoryChild::class;
    }

    /**
     * Check exists
     *
     * @param $key
     * @param $value
     * @param $userId
     * @return mixed
     */
    public function checkExists($key, $value, $id)
    {
        return $this->_model->where($key, $value)->where('category_child.id', '!=', $id)->first();
    }

    /**
     * Find one
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public function findOne($key, $value, $categoryId, $id)
    {
        return $this->_model
                    ->where('category_id', $categoryId)
                    ->where('id', '!=', $id)
                    ->where($key, $value)->first();
    }

    /**
     * Find names
     * @param $names
     * @param $categoryId
     * @return mixed
     */
    public function findNames($names, $categoryId)
    {
        return $this->_model
                    ->where('category_id', $categoryId)
                    ->whereIn('name', $names)->count();
    }

    /**
     * Check exists name
     * @param $name
     * @param $id
     * @param $categoryId
     * @return mixed
     */
    public function checkExistsName($names, $categoryId, $id)
    {
        return $this->_model
                    ->where('category_id', $categoryId)
                    ->whereIn('name', $names)
                    ->where('id', '!=', $id)->count();
    }
}

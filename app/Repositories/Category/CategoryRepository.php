<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\DB;

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
    public function checkExists($key, $value, $id)
    {
        return $this->_model->where($key, $value)->where('categories.id', '!=', $id)->first();
    }

    /**
     * Find one
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public function findOne($key, $value)
    {
        return $this->_model->where($key, $value)->first();
    }


    /**
     * Get list category
     *
     * @param $request
     * @return mixed
     */
    public function getListCategory($request)
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');
        $query = $this->_model
            ->select(
                'id',
                'name',
                DB::raw("CONCAT('$url', image) as image")
            );

        return $query->orderBy('categories.id', 'desc');
    }

    /**
     * Detail
     *
     * @param $id
     * @return mixed
     */
    public function detail($id)
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');
        return $this->_model
            ->select(
                'categories.id',
                'categories.name',
                DB::raw("CONCAT('$url', categories.image) as image"),
            )
            ->with(['categoryChildren' => function ($query) {
                return $query->select('category_child.id', 'category_child.name', 'category_child.category_id');
            }])
            ->where('categories.id', $id)
            ->first();
    }

    public function getCategoryPublic($request, $id)
    {
        $qb = $this->_model
            ->select('categories.id', 'categories.name')
            ->where('categories.id', $id)
            ->with(['categoryChildren']);
        return $qb->first();
    }
}

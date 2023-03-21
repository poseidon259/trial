<?php

namespace App\Repositories\ProductFavorite;

use App\Models\ProductFavorite;
use App\Repositories\Base\BaseRepository;

class ProductFavoriteRepository extends BaseRepository implements ProductFavoriteRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ProductFavorite::class;
    }

    /**
     * Check exists
     *
     * @param $key
     * @param $value
     * @param $userId
     * @return mixed
     */
    public function checkExists($key, $value, $id) {
        return $this->_model->where($key, $value)->where('user_favorite_product.id', '!=', $id)->first();
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
     * List
     *
     * @param $request
     * @param $user
     * @return mixed
     */
    public function list($user) {
        $query = $this->_model
            ->select(
                'user_favorite_product.id',
                'products.id as product_id',
                'products.name',
                'products.description_list',
            )
            ->join('products', 'products.id', '=', 'user_favorite_product.product_id')
            ->where('user_favorite_product.user_id', $user->id)
            ;


        return $query->get();
    }
}
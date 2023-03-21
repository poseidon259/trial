<?php

namespace App\Repositories\ProductImage;

use App\Models\ProductImage;
use App\Repositories\Base\BaseRepository;

class ProductImageRepository extends BaseRepository implements ProductImageRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ProductImage::class;
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
        return $this->_model->where($key, $value)->where('product_images.id', '!=', $id)->first();
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
     * Get ids by product id
     *
     * @param $productId
     * @return mixed
     */
    public function getIdsByProductId($productId) {
        return $this->_model->where('product_id', $productId)->pluck('id');
    }
}
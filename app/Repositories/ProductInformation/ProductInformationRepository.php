<?php

namespace App\Repositories\ProductInformation;

use App\Models\ProductInformation;
use App\Repositories\Base\BaseRepository;
use App\Repositories\ProductInformation\ProductInformationRepositoryInterface;

class ProductInformationRepository extends BaseRepository implements ProductInformationRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ProductInformation::class;
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
        return $this->_model->where($key, $value)->where('product_information.id', '!=', $id)->first();
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
}
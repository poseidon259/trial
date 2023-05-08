<?php

namespace App\Repositories\District;

use App\Models\District;
use App\Repositories\Base\BaseRepository;

class DistrictRepository extends BaseRepository implements DistrictRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return District::class;
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
        return $this->_model->where($key, $value)->where('product_information.id', '!=', $id)->first();
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

    public function getDistrictsByProvince($provinceId)
    {
        return $this->_model->where('province_id', $provinceId)->get();
    }
}

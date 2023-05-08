<?php

namespace App\Repositories\Ward;

use App\Models\Ward;
use App\Repositories\Base\BaseRepository;

class WardRepository extends BaseRepository implements WardRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Ward::class;
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

    public function getWardsByDistrict( $districtId)
    {
        return $this->_model
            ->where('wards.district_id', $districtId)
            ->get();
    }
}

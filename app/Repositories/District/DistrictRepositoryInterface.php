<?php

namespace App\Repositories\District;

use App\Repositories\Base\BaseRepositoryInterface;

interface DistrictRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);

    public function getDistrictsByProvince($provinceId);
}

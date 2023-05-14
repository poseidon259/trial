<?php

namespace App\Repositories\Ward;

use App\Repositories\Base\BaseRepositoryInterface;

interface WardRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);

    public function getWardsByDistrict($districtId);
}

<?php

namespace App\Repositories\Ward;

interface WardRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);

    public function getWardsByDistrict($districtId);
}

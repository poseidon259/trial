<?php

namespace App\Repositories\Province;

interface ProvinceRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);
}

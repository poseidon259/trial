<?php

namespace App\Repositories\Province;

use App\Repositories\Base\BaseRepositoryInterface;

interface ProvinceRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);
}

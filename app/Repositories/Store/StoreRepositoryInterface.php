<?php

namespace App\Repositories\Store;

use App\Repositories\Base\BaseRepositoryInterface;

interface StoreRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function findOne($key, $value);

    public function checkExists($key, $value, $id);

    public function getListStore($request);
}
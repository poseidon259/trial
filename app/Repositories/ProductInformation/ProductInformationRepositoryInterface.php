<?php

namespace App\Repositories\ProductInformation;

use App\Repositories\Base\BaseRepositoryInterface;

interface ProductInformationRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $userId);

    public function findOne($key, $value);
}
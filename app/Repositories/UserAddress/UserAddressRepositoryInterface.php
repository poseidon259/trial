<?php

namespace App\Repositories\UserAddress;

use App\Repositories\Base\BaseRepositoryInterface;

interface UserAddressRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);

    public function getListUserAddress($request);

    public function getListByUserId($userId);

    public function updateDefault($userId, $id);
}
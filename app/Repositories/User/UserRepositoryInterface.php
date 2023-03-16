<?php

namespace App\Repositories\User;

use App\Repositories\Base\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $userId);

    public function findOne($key, $value);

    public function getListUser($request);

    public function updateDefault($userId, $id);
}
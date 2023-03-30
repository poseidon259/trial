<?php

namespace App\Repositories\Cart;

use App\Repositories\Base\BaseRepositoryInterface;

interface CartRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);

    public function getCart($userId);
}
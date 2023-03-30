<?php

namespace App\Repositories\OrderItem;

use App\Repositories\Base\BaseRepositoryInterface;

interface OrderItemRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);

    public function detail($id);
}
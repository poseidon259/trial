<?php

namespace App\Repositories\CartItem;

use App\Repositories\Base\BaseRepositoryInterface;

interface CartItemRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);

    public function getItem($productId, $fieldId, $cartId);
}

<?php

namespace App\Repositories\ProductImage;

use App\Repositories\Base\BaseRepositoryInterface;

interface ProductImageRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);

    public function getIdsByProductId($productId);
}
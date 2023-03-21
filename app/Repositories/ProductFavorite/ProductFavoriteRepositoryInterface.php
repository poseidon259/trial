<?php

namespace App\Repositories\ProductFavorite;

use App\Repositories\Base\BaseRepositoryInterface;

interface ProductFavoriteRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);

    public function list($user);
}
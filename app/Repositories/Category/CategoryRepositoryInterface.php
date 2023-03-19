<?php

namespace App\Repositories\Category;

use App\Repositories\Base\BaseRepositoryInterface;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $userId);

    public function findOne($key, $value);

    public function getListCategory($request);

    public function detail($id);
}
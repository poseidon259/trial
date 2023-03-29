<?php

namespace App\Repositories\CategoryChild;

use App\Repositories\Base\BaseRepositoryInterface;

interface CategoryChildRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value, $categoryId);

    public function findNames($name, $categoryId);

    public function checkExistsName($names, $categoryId, $id);
}
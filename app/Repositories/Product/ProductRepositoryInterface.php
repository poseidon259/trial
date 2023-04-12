<?php

namespace App\Repositories\Product;

use App\Repositories\Base\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $userId);

    public function findOne($key, $value);

    public function getListProduct($request);

    public function detail($id);

    public function countByCategory($categoryId);

    public function countByChildCategory($childCategoryId);

    public function getListProductPublic($request);

    public function detailProductPublic($id);

    public function getListProductByCategory($request, $categoryId);
}

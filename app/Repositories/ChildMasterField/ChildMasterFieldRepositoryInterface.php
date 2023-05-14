<?php

namespace App\Repositories\ChildMasterField;

use App\Repositories\Base\BaseRepositoryInterface;

interface ChildMasterFieldRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);

    public function detail($productId, $id);

    public function getField($productId, $fieldId);

    public function getListChildMasterField($productId, $fieldId);

    public function deleteChildMasterField($id);
}

<?php

namespace App\Repositories\MasterField;

use App\Repositories\Base\BaseRepositoryInterface;

interface MasterFieldRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);

}

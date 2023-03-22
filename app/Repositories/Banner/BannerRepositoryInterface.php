<?php

namespace App\Repositories\Banner;

use App\Repositories\Base\BaseRepositoryInterface;

interface BannerRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);

    public function getListBanner();

    public function deleteIds($ids);

    public function list();
}
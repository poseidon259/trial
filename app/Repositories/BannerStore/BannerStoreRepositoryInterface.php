<?php

namespace App\Repositories\BannerStore;

use App\Repositories\Base\BaseRepositoryInterface;

interface BannerStoreRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);

    public function getListBanner($storeId);

    public function deleteIds($ids);

    public function list($storeId);

    public function getListBannerStorePublic($storeId);
}

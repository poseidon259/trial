<?php

namespace App\Repositories\BannerStore;

use App\Models\BannerStore;
use App\Repositories\BannerStore\BannerStoreRepositoryInterface;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\DB;

class BannerStoreRepository extends BaseRepository implements BannerStoreRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return BannerStore::class;
    }

    /**
     * Check exists
     *
     * @param $key
     * @param $value
     * @param $userId
     * @return mixed
     */
    public function checkExists($key, $value, $id)
    {
        return $this->_model->where($key, $value)->where('banner_stores.id', '!=', $id)->first();
    }

    /**
     * Find one
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public function findOne($key, $value)
    {
        return $this->_model->where($key, $value)->first();
    }

    /**
     * Get list banner
     *
     * @return mixed
     */
    public function getListBanner($storeId)
    {
        return $this->_model
            ->where('store_id', $storeId)
            ->orderBy('sort', 'asc')->get();
    }

    /**
     * Delete ids
     *
     * @param $ids
     * @return mixed
     */
    public function deleteIds($ids)
    {
        return $this->_model->whereIn('id', $ids)->delete();
    }

    /**
     * List
     *
     * @return mixed
     */
    public function list($storeId)
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');

        $query = $this->_model
            ->select(
                'id',
                DB::raw('CONCAT("' . $url . '", image) as image'),
                'sort',
                'created_at',
                'updated_at')
            ->where('display', BANNER_ACTIVE)
            ->where('store_id', $storeId)
            ->orderBy('sort', 'asc')->get();

        return $query;
    }

    public function getListBannerStorePublic($storeId)
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');

        $query = $this->_model
            ->select(
                'id',
                DB::raw('CONCAT("' . $url . '", image) as image'),
                'sort',
                'created_at',
                'updated_at')
            ->where('display', BANNER_ACTIVE)
            ->where('store_id', $storeId)
            ->orderBy('sort', 'asc')->get();

        return $query;
    }
}

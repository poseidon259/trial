<?php

namespace App\Repositories\Banner;

use App\Models\Banner;
use App\Repositories\Banner\BannerRepositoryInterface;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\DB;

class BannerRepository extends BaseRepository implements BannerRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Banner::class;
    }

    /**
     * Check exists
     *
     * @param $key
     * @param $value
     * @param $userId
     * @return mixed
     */
    public function checkExists($key, $value, $id) {
        return $this->_model->where($key, $value)->where('users.id', '!=', $id)->first();
    }

    /**
     * Find one
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public function findOne($key, $value) {
        return $this->_model->where($key, $value)->first();
    }

    /**
     * Get list banner
     *
     * @return mixed
     */
    public function getListBanner() {
        return $this->_model->orderBy('sort', 'asc')->get();
    }

    /**
     * Delete ids
     *
     * @param $ids
     * @return mixed
     */
    public function deleteIds($ids) {
        return $this->_model->whereIn('id', $ids)->delete();
    }

    /**
     * List
     *
     * @return mixed
     */
    public function list() {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');

        $query = $this->_model
                ->select(
                    'id',
                    DB::raw('CONCAT("'.$url.'", image) as image'),
                    'sort',
                    'created_at',
                    'updated_at')
                ->where('display', BANNER_ACTIVE)
                ->orderBy('sort', 'asc')->get();

        return $query;
    }
}
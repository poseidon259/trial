<?php

namespace App\Repositories\CommentImage;

use App\Models\CommentImage;
use App\Repositories\Base\BaseRepository;

class CommentImageRepository extends BaseRepository implements CommentImageRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return CommentImage::class;
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
        return $this->_model->where($key, $value)->where('banner_general.id', '!=', $id)->first();
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
}
<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Repositories\Base\BaseRepository;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Comment::class;
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
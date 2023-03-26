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
    public function checkExists($key, $value, $id)
    {
        return $this->_model->where($key, $value)->where('comment_images.id', '!=', $id)->first();
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
     * Get list by comment id
     *
     * @param $commentId
     * @return mixed
     */
    public function getListByCommentId($commentId)
    {
        return $this->_model->where('comment_id', $commentId)->get();
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
}

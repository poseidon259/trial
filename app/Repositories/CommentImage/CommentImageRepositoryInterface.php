<?php

namespace App\Repositories\CommentImage;

use App\Repositories\Base\BaseRepositoryInterface;

interface CommentImageRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);

    public function getListByCommentId($commentId);

    public function deleteIds($ids);
}

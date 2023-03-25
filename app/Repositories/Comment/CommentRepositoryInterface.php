<?php

namespace App\Repositories\Comment;

use App\Repositories\Base\BaseRepositoryInterface;

interface CommentRepositoryInterface extends BaseRepositoryInterface
{
    public function getModel();

    public function checkExists($key, $value, $id);

    public function findOne($key, $value);

    public function detail($id);

    public function getListCommentByProductId($request, $productId);
}

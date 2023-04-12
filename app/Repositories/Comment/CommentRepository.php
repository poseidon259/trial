<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\DB;

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
    public function checkExists($key, $value, $id)
    {
        return $this->_model->where($key, $value)->where('comments.id', '!=', $id)->first();
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
     * Detail
     *
     * @param $id
     * @return mixed
     */
    public function detail($id)
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');

        return $this->_model
            ->select(
                'id',
                'product_id',
                'content',
                'rating',
                'status',
                'first_name',
                'last_name',
                'fake_avatar'
                // DB::raw('CONCAT("' . $url . '", fake_avatar) as fake_avatar'),
            )
            ->with(['commentImages' => function ($query) use ($url) {
                $query->select(
                    'id',
                    'comment_id',
                    'image',
                    DB::raw('CONCAT("' . $url . '", image) as image')
                );
            }])
            ->where('id', $id)
            ->first();
    }

    /**
     * Get list comment by product id
     *
     * @param $productId
     * @return mixed
     */
    public function getListCommentByProductId($request, $productId)
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');

        return $this->_model
            ->select(
                'id',
                'product_id',
                'content',
                'rating',
                'status',
                'first_name',
                'last_name',
                'fake_avatar'
            )
            ->with(['commentImages' => function ($query) use ($url) {
                $query->select(
                    'id',
                    'comment_id',
                    'image',
                    DB::raw('CONCAT("' . $url . '", image) as image')
                );
            }])
            ->where('product_id', $productId);
    }

    public function getListCommentPublic($productId)
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');

        return $this->_model
            ->where('comments.status', COMMENT_ACTIVE)
            ->select(
                'id',
                'content',
                'rating',
                'first_name',
                'last_name',
                'fake_avatar',
                'updated_at'
            )
            ->with(['commentImages' => function ($query) use ($url) {
                $query->select(
                    'id',
                    'comment_id',
                    'image',
                    DB::raw('CONCAT("' . $url . '", image) as image')
                );
            }])
            ->where('product_id', $productId)
            ->orderBy('updated_at', 'DESC')
            ;
    }
}

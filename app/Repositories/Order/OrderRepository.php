<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Order::class;
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
        return $this->_model->where($key, $value)->where('orders.id', '!=', $id)->first();
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
}

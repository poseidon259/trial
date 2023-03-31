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

        return $this->_model
            ->select(
                'id',
                'order_no',
                'first_name',
                'last_name',
                'email',
                'phone_number',
                'province_id',
                'district_id',
                'ward_id',
                'house_number',
                'discount',
                'delivery_fee',
                'discount_freeship',
                'payment_date',
                'status',
                'payment_method',
                'sub_total',
                'total',
                'note',
            )
            ->with(['orderItems'])
            ->where('id', $id)
            ->first();
    }
}

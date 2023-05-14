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
            ->with(['orderItems' => function ($q) use ($url) {
                return $q
                    ->leftJoin('child_master_fields', 'child_master_fields.id', '=', 'order_items.child_master_field_id')
                    ->leftJoin('master_fields', 'master_fields.id', '=', 'child_master_fields.master_field_id')
                    ->select(
                        'order_items.id as id',
                        'order_items.product_id as product_id',
                        'order_items.product_name',
                        'order_items.order_id as order_id',
                        'child_master_fields.id as child_master_field_id',
                        'master_fields.id as master_field_id',
                        'child_master_fields.name as child_master_field_name',
                        'master_fields.name as master_field_name',
                        'order_items.quantity',
                        'order_items.sale_price',
                        'order_items.origin_price',
                        'order_items.total',
                    )
                    ->with(['productImages' => function ($qb) use ($url) {
                        return $qb->select(
                            'product_images.id',
                            'product_images.product_id',
                            DB::raw('CONCAT("' . $url . '", product_images.image) as image'),
                        );
                    }]);
            }])
            ->where('id', $id)
            ->first();
    }

    /**
     * Get list order
     *
     * @param $request
     * @return mixed
     */
    public function getListOrder($request)
    {
        $query = $this->_model
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
            ->with(['orderItems']);

        if ($request->keyword) {
            $query->where('order_no', 'like', '%' . $request->keyword . '%');
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        return $query->orderBy('orders.created_at', 'desc');
    }

    public function detailOrderPublic($id, $userId)
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');

        return $this->_model
            ->join('provinces', 'provinces.id', '=', 'orders.province_id')
            ->join('districts', 'districts.id', '=', 'orders.district_id')
            ->join('wards', 'wards.id', '=', 'orders.ward_id')
            ->where('orders.user_id', $userId)
            ->where('orders.id', $id)
            ->select(
                'orders.id',
                'orders.order_no',
                'orders.first_name',
                'orders.last_name',
                'orders.email',
                'orders.phone_number',
                'provinces.name as province_name',
                'districts.name as district_name',
                'wards.name as ward_name',
                'orders.house_number',
                'orders.discount',
                'orders.delivery_fee',
                'orders.discount_freeship',
                'orders.payment_date',
                'orders.status',
                'orders.payment_method',
                'orders.sub_total',
                'orders.total',
                'orders.note',
            )
            ->with(['orderItems' => function ($q) use ($url) {
                return $q
                    ->leftJoin('child_master_fields', 'child_master_fields.id', '=', 'order_items.child_master_field_id')
                    ->leftJoin('master_fields', 'master_fields.id', '=', 'child_master_fields.master_field_id')
                    ->select(
                        'order_items.id as id',
                        'order_items.product_id as product_id',
                        'order_items.product_name',
                        'order_items.order_id as order_id',
                        'child_master_fields.id as child_master_field_id',
                        'master_fields.id as master_field_id',
                        'child_master_fields.name as child_master_field_name',
                        'master_fields.name as master_field_name',
                        'order_items.quantity',
                        'order_items.sale_price',
                        'order_items.origin_price'
                    )
                    ->with(['productImages' => function ($qb) use ($url) {
                        return $qb->select(
                            'product_images.id',
                            'product_images.product_id',
                            DB::raw('CONCAT("' . $url . '", product_images.image) as image'),
                        );
                    }]);
            }])
            ->first();
    }

    public function listOrderHistory($request, $userId)
    {
        $query = $this->_model
            ->where('user_id', $userId)
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
                'created_at',
                'updated_at'
            );

        if ($request->keyword) {
            $query->where('order_no', 'like', '%' . $request->keyword . '%');
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        return $query->orderBy('created_at', 'desc');
    }
}

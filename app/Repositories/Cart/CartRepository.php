<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Cart\CartItemRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Cart::class;
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
        return $this->_model->where($key, $value)->where('cart.id', '!=', $id)->first();
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

    public function getCart($userId)
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');
        return $this->_model
                    ->join('cart_items', 'cart_items.cart_id', '=', 'cart.id')
                    ->join('product_information as pi', 'pi.product_id', '=', 'cart_items.product_id')
                    ->select(
                        'cart.id',
                        DB::raw('SUM((CASE WHEN pi.sale_price IS NOT NULL THEN pi.sale_price ELSE pi.origin_price END) * cart_items.quantity) as sub_price'),
                    )
                    ->where('cart.user_id', $userId)
                    ->groupBy('cart.id')
                    ->with(['cartItems' => function($q) use ($url) {
                        return $q
                            ->leftJoin('child_master_fields', 'child_master_fields.id', '=', 'cart_items.id')
                            ->leftJoin('master_fields', 'master_fields.id', '=', 'child_master_fields.master_field_id')
                            ->join('product_information as pi', 'pi.product_id', '=', 'cart_items.product_id')
                            ->join('products as p', 'p.id', '=', 'pi.product_id')
                            ->select(
                                'cart_items.id',
                                'cart_items.cart_id',
                                'cart_items.product_id',
                                'p.name as product_name',
                                'cart_items.quantity',
                                'pi.sale_price',
                                'pi.origin_price',
                                'master_fields.name as master_field_name',
                                'child_master_fields.name as child_master_field_name'
                            )
                            ->with(['productImages' => function($qb) use ($url) {
                                return $qb->select(
                                    'product_images.id',
                                    'product_images.product_id',
                                    DB::raw('CONCAT("' . $url . '", product_images.image) as image'),
                                )
                                ->get()
                                ;
                            }])
                            ;
                    }])
                    ->first()
                    ;
    }
}

<?php

namespace App\Repositories\CartItem;

use App\Models\CartItem;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\DB;

class CartItemRepository extends BaseRepository implements CartItemRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return CartItem::class;
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

    public function getItem($productId, $fieldId)
    {
        $query = $this->_model->where('product_id', $productId);

        if ($fieldId) {
            $query->where('child_master_field_id', $fieldId);
        }

        return $query->first();
    }
}

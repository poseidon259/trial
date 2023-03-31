<?php

namespace App\Repositories\ChildMasterField;

use App\Models\ChildMasterField;
use App\Repositories\Base\BaseRepository;
use App\Repositories\ChildMasterField\ChildMasterFieldRepositoryInterface;

class ChildMasterFieldRepository extends BaseRepository implements ChildMasterFieldRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ChildMasterField::class;
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
        return $this->_model->where($key, $value)->where('child_master_fields.id', '!=', $id)->first();
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
     * @param $productId
     * @param $id
     * @return mixed
     */
    public function detail($productId, $id)
    {
        return $this->_model
                    ->join('master_fields', 'child_master_fields.master_field_id', '=', 'master_fields.id')
                    ->join('products', 'child_master_fields.product_id', '=', 'products.id')
                    ->where('child_master_fields.product_id', $productId)
                    ->where('child_master_fields.id', $id)
                    ->select(
                        'child_master_fields.id',
                        'master_fields.name as master_field_name',
                        'products.name as product_name',
                        'child_master_fields.name',
                        'child_master_fields.sale_price',
                        'child_master_fields.origin_price',
                        'child_master_fields.stock',
                        'child_master_fields.product_code',
                    )
                    ->first();
    }
}

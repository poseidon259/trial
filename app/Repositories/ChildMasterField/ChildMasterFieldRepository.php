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
}

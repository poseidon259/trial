<?php

namespace App\Repositories\MasterField;

use App\Models\MasterField;
use App\Repositories\Base\BaseRepository;
use App\Repositories\MasterField\MasterFieldRepositoryInterface;

class MasterFieldRepository extends BaseRepository implements MasterFieldRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return MasterField::class;
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
        return $this->_model->where($key, $value)->where('master_fields.id', '!=', $id)->first();
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

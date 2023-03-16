<?php

namespace App\Repositories\UserAddress;

use App\Models\UserAddress;
use App\Repositories\Base\BaseRepository;
use App\Repositories\UserAddress\UserAddressRepositoryInterface;

class UserAddressRepository extends BaseRepository implements UserAddressRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return UserAddress::class;
    }

    /**
     * Check exists
     *
     * @param $key
     * @param $value
     * @param $id
     * @return mixed
     */
    public function checkExists($key, $value, $id) {
        return $this->_model->where($key, $value)->where('users_address.id', '!=', $id)->first();
    }

    /**
     * Find one
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public function findOne($key, $value) {
        return $this->_model->where($key, $value)->first();
    }

    /**
     * Get list user address
     *
     * @param $request
     * @return mixed
     */
    public function getListUserAddress($request) {
        return null;
    }

    /**
     * Get list by user id
     *
     * @param $userId
     * @return mixed
     */
    public function getListByUserId($userId) {
        $qb = $this->_model
                    ->select(
                    'users_address.id',
                    'user_id',
                    'first_name',
                    'last_name',
                    'phone_number',
                    'province_id',
                    'district_id',
                    'ward_id',
                    'house_number',
                    'is_default',
                    'postal_code'
                    )
                ->where('user_id', $userId);

        return  $qb->orderBy('updated_at', 'desc')->get();
    }

    public function updateDefault($userId, $id) {
        $this->_model->where('user_id', $userId)->where('users_address.id', '!=', $id )->update(['is_default' => ADDRESS_NOT_DEFAULT]);
    }
}
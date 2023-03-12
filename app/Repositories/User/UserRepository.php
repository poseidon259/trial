<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return User::class;
    }

    /**
     * Check exists
     *
     * @param $key
     * @param $value
     * @param $userId
     * @return mixed
     */
    public function checkExists($key, $value, $userId) {
        return $this->_model->where($key, $value)->where('users.id', '!=', $userId)->first();
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
     * Get list user
     *
     * @param $request
     * @return mixed
     */
    public function getListUser($request) {
        $keyword = strtolower($request->keyword);
        $keyword = str_replace(' ', '', $keyword);
        $keyword = str_replace(',', '', $keyword);

        $query = $this->_model
                ->join('roles', 'roles.id', '=', 'users.role_id')
                ->select(
                    'users.id',
                    'email',
                    'first_name',
                    'last_name',
                    'phone_number',
                    'status',
                    'users.role_id',
                    'roles.name as role_name',
                    'users.created_at',
                    'users.updated_at',
                    'store_id',
                    'birthday',
                    'user_name',
                    'postal_code',
                    'province_id',
                    'district_id',
                    'ward_id',
                    'house_number',
                    'avatar',
                    'gender'
                );

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->role_id) {
            $query->where('users.role_id', $request->role_id);
        }

        if ($request->store_id) {
            $query->where('store_id', $request->store_id);
        }

        if ($request->start_date) {
            $query->where('users.created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('users.created_at', '<=', $request->end_date);
        }

        if ($keyword) {
            $query->whereRaw("LOWER(CONCAT(first_name, last_name, email, phone_number)) LIKE '%{$keyword}%'");
        }

        return $query
                ->orderBy(DB::raw('CASE WHEN status = ' . INACTIVE . ' THEN 3 ELSE users.status END'), 'asc')
                ->orderBy('users.created_at', 'desc');
    }
}
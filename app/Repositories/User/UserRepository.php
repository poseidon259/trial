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
    public function checkExists($key, $value, $userId)
    {
        return $this->_model->where($key, $value)->where('users.id', '!=', $userId)->first();
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
     * Get list user
     *
     * @param $request
     * @return mixed
     */
    public function getListUser($request)
    {
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
                    ->join('roles', 'roles.id', '=', 'users.role_id')
                    ->join('provinces', 'provinces.id', '=', 'users.province_id')
                    ->join('districts', 'districts.id', '=', 'users.district_id')
                    ->join('wards', 'wards.id', '=', 'users.ward_id')
                    ->select(
                        'users.id',
                        'email',
                        'first_name',
                        'last_name',
                        'phone_number',
                        'status',
                        'users.role_id',
                        'users.created_at',
                        'users.updated_at',
                        'store_id',
                        'birthday',
                        'user_name',
                        'postal_code',
                        'users.province_id',
                        'users.district_id',
                        'users.ward_id',
                        'provinces.name as province_name',
                        'districts.name as district_name',
                        'wards.name as ward_name',
                        'house_number',
                        DB::raw('CONCAT("' . $url . '", avatar) as avatar'),
                        'gender',
                    )
                    ->with(['userAddress' => function ($q) {
                        return $q
                                ->join('provinces', 'provinces.id', '=', 'users_address.province_id')
                                ->join('districts', 'districts.id', '=', 'users_address.district_id')
                                ->join('wards', 'wards.id', '=', 'users_address.ward_id')
                                ->select(
                                    'users_address.id',
                                    'users_address.user_id',
                                    'users_address.province_id',
                                    'users_address.district_id',
                                    'users_address.ward_id',
                                    'users_address.house_number',
                                    'users_address.postal_code',
                                    'provinces.name as province_name',
                                    'districts.name as district_name',
                                    'wards.name as ward_name',
                                    'users_address.is_default'
                                );
                    }])
                    ->where('users.id', $id)
                    ->first();
    }
}

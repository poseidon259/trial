<?php

namespace App\Repositories\Store;

use App\Models\Store;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\DB;

class StoreRepository extends BaseRepository implements StoreRepositoryInterface
{
    public function getModel()
    {
        return Store::class;
    }

    public function findOne($key, $value)
    {
        return $this->_model->where($key, $value)->first();
    }

    public function checkExists($key, $value, $id)
    {
        return $this->_model->where($key, $value)->where('stores.id', '!=', $id)->first();
    }

    public function getListStore($request)
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');
        $keyword = strtolower($request->keyword);
        $keyword = str_replace(' ', '', $keyword);
        $keyword = str_replace(',', '', $keyword);

        $query = $this->_model
            ->select(
                'stores.id',
                'email',
                'manager_name',
                'phone_number',
                'company_name',
                'province_id',
                'district_id',
                'ward_id',
                'house_number',
                'description_list',
                'description_detail',
                DB::raw('CONCAT("' . $url .'", logo) as logo'),
                DB::raw('CONCAT("' . $url .'", background_image) as background_image'),
                'status',
                'created_at',
                'updated_at'
            );

        if ($keyword) {
            $query->whereRaw("LOWER(CONCAT(manager_name, company_name, email, phone_number)) LIKE '%{$keyword}%'");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->start_date) {
            $query->where('stores.created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('stores.created_at', '<=', $request->end_date);
        }

        return $query->orderBy('stores.created_at', 'desc');
    }
}

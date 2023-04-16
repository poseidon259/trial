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
                DB::raw('CONCAT("' . $url . '", logo) as logo'),
                DB::raw('CONCAT("' . $url . '", background_image) as background_image'),
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

    public function getStoreHomepage()
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');
        return $this->_model
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
                DB::raw('CONCAT("' . $url . '", logo) as logo'),
                DB::raw('CONCAT("' . $url . '", background_image) as background_image'),
                'status',
                'created_at',
                'updated_at'
            )
            ->with(['products' => function ($q) use ($url) {
                return $q->with(['productImages' => function ($q1) use ($url) {
                    return $q1->select(
                        'product_images.id',
                        'product_images.product_id',
                        DB::raw('CONCAT("' . $url . '", product_images.image) as image'),
                    );
                }]);
            }])
            ->whereHas('products', function ($q) {
                return $q->havingRaw('COUNT(*) >= 3');
            })
            ->limit(10)
            ->orderBy('stores.updated_at', 'DESC')
            ->get();
    }

    public function detailStorePublic($id)
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');

        $query = $this->_model
            ->select(
                'stores.id',
                'stores.email',
                'stores.manager_name',
                'stores.phone_number',
                'stores.company_name',
                'stores.province_id',
                'stores.district_id',
                'stores.ward_id',
                'stores.house_number',
                'stores.description_list',
                'stores.description_detail',
                DB::raw('CONCAT("' . $url . '", logo) as logo'),
                DB::raw('CONCAT("' . $url . '", background_image) as background_image'),
                'stores.status',
                'stores.created_at',
                'stores.updated_at'
            )
            ->with(['products' => function ($q) use ($url) {
                return $q->with(['productImages' => function ($q1) use ($url) {
                    return $q1->select(
                        'product_images.id',
                        'product_images.product_id',
                        DB::raw('CONCAT("' . $url . '", product_images.image) as image'),
                    );
                }]);
            }])
            ->whereHas('products', function ($q) {
                return $q->havingRaw('COUNT(*) >= 3');
            })
            ->first();

        return $query;
    }
}

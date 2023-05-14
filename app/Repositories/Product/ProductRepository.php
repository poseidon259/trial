<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Product::class;
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
     * Get list product
     *
     * @param $request
     * @return mixed
     */
    public function getListProduct($request)
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');
        $keyword = strtolower($request->keyword);
        $keyword = str_replace(' ', '', $keyword);
        $keyword = str_replace(',', '', $keyword);

        $query = $this->_model
            ->join('product_information', 'products.id', '=', 'product_information.product_id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.id',
                'products.name',
                'products.category_id',
                'categories.name as category_name',
                'products.status',
                'products.created_by',
                'products.description_list',
                'products.description_detail',
                'product_information.product_code',
                'product_information.sale_price',
                'product_information.origin_price',
                'product_information.stock',
                'products.description_list',
                'products.description_detail',
                'products.created_at',
                'products.updated_at',
            )
            ->with(['productImages' => function ($q) use ($url) {
                return $q->select(
                    'product_images.id',
                    'product_images.product_id',
                    DB::raw('CONCAT("' . $url . '", product_images.image) as image'),
                    'product_images.type',
                    'product_images.sort',
                    'product_images.status'
                );
            }, 'masterFields' => function ($q) {
                return $q->select(
                    'master_fields.id',
                    'master_fields.product_id',
                    'master_fields.name',
                )->with(['childs' => function ($qc) {
                    return $qc->select(
                        'child_master_fields.id',
                        'child_master_fields.name',
                        'child_master_fields.master_field_id',
                        'child_master_fields.sale_price',
                        'child_master_fields.origin_price',
                        'child_master_fields.stock',
                    );
                }]);
            }]);

        if ($request->keyword) {
            $query->whereRaw("LOWER(CONCAT(products.name, product_information.product_code)) LIKE  '%{$keyword}%'");
        }

        if ($request->status) {
            $query->where('products.status', $request->status);
        }

        if ($request->start_date) {
            $query->where('products.created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('products.created_at', '<=', $request->end_date);
        }

        if ($request->category_id) {
            $query->where('products.category_id', $request->category_id);
        }

        if ($request->created_by) {
            $query->where('products.created_by', $request->created_by);
        }

        return $query->orderBy('products.updated_at', 'desc');
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
            ->join('product_information', 'products.id', '=', 'product_information.product_id')
            ->select(
                'products.id',
                'products.name',
                'products.category_id',
                'products.status',
                'products.created_by',
                'products.description_list',
                'products.description_detail',
                'product_information.id as product_information_id',
                'product_information.product_code',
                'product_information.sale_price',
                'product_information.origin_price',
                'product_information.stock',
                'products.description_list',
                'products.description_detail',
                'products.created_at',
                'products.updated_at',
            )
            ->with(['productImages' => function ($q) use ($url) {
                return $q->select(
                    'product_images.id',
                    'product_images.product_id',
                    DB::raw('CONCAT("' . $url . '", product_images.image) as image'),
                    'product_images.type',
                    'product_images.sort',
                    'product_images.status'
                );
            }, 'masterFields' => function ($q) {
                return $q->select(
                    'master_fields.id',
                    'master_fields.product_id',
                    'master_fields.name',
                )->with(['childs' => function ($qc) {
                    return $qc->select(
                        'child_master_fields.id',
                        'child_master_fields.name',
                        'child_master_fields.master_field_id',
                        'child_master_fields.sale_price',
                        'child_master_fields.origin_price',
                        'child_master_fields.stock',
                    );
                }]);
            }])
            ->where('products.id', $id)
            ->first();
    }

    /**
     * count product by category
     *
     * @param $categoryId
     * @return mixed
     */
    public function countByCategory($categoryId)
    {
        return $this->_model->where('category_id', $categoryId)->count();
    }

    /**
     * count product by child category
     *
     * @param $childCategoryId
     * @return mixed
     */
    public function countByChildCategory($childCategoryId)
    {
        return $this->_model->where('child_category_id', $childCategoryId)->count();
    }

    /**
     * Get list product public
     *
     * @param $request
     * @return mixed
     */
    public function getListProductPublic($request)
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');

        $query = $this->_model
            ->join('product_information', 'products.id', '=', 'product_information.product_id')
            ->where('products.status', PRODUCT_ACTIVE)
            ->select(
                'products.id',
                'products.name',
                'products.category_id',
                'products.status',
                'products.created_by',
                'products.description_detail',
                'product_information.product_code',
                'product_information.sale_price',
                'product_information.origin_price',
                'product_information.stock',
                'products.description_list',
                'products.created_at',
                'products.updated_at',
            )
            ->with(['productImages' => function ($q) use ($url) {
                return $q->select(
                    'product_images.id',
                    'product_images.product_id',
                    DB::raw('CONCAT("' . $url . '", product_images.image) as image'),
                    'product_images.type',
                    'product_images.sort',
                    'product_images.status'
                );
            }])
            ->withCount('comments')
            ->withAvg('comments as avg_rating', 'rating')
            ->groupBy('products.id');

        return $query->orderBy('products.updated_at', 'desc');
    }

    public function detailProductPublic($request, $id)
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');
        $query = $this->_model
            ->join('product_information', 'products.id', '=', 'product_information.product_id')
            ->where('products.status', PRODUCT_ACTIVE)
            ->where('products.id', $id)
            ->select(
                'products.id',
                'products.name',
                'products.category_id',
                'products.status',
                'products.created_by',
                'products.description_detail',
                'products.description_list',
                'products.created_at',
                'products.updated_at',
            )
            ->when($request->child_master_field_id, function ($query) {
                return $query
                    ->join('child_master_fields', 'child_master_fields.product_id', 'products.id')
                    ->join('master_fields', 'master_fields.id', 'child_master_fields.master_field_id')
                    ->addSelect('master_fields.name as master_field_name',
                        'child_master_fields.name as child_master_field_name',
                        'child_master_fields.sale_price',
                        'child_master_fields.origin_price',
                        'child_master_fields.stock'
                    );
            }, function ($query) {
                return $query->addSelect(
                    'product_information.product_code',
                    'product_information.sale_price',
                    'product_information.origin_price',
                    'product_information.stock',
                );
            })
            ->with(['productImages' => function ($q) use ($url) {
                return $q->select(
                    'product_images.id',
                    'product_images.product_id',
                    DB::raw('CONCAT("' . $url . '", product_images.image) as image'),
                    'product_images.type',
                    'product_images.sort',
                    'product_images.status'
                );
            }, 'masterFields' => function ($q) {
                return $q->select(
                    'master_fields.id',
                    'master_fields.product_id',
                    'master_fields.name',
                )->with(['childs' => function ($qc) {
                    return $qc->select(
                        'child_master_fields.id',
                        'child_master_fields.name',
                        'child_master_fields.master_field_id',
                        'child_master_fields.sale_price',
                        'child_master_fields.origin_price',
                        'child_master_fields.stock',
                    );
                }]);
            }])
            ->withCount('comments')
            ->withAvg('comments as avg_rating', 'rating')
            ->groupBy('products.id');


        return $query->first();
    }

    public function getListProductByCategory($request, $categoryId)
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');
        $query = $this->_model
            ->join('product_information', 'products.id', '=', 'product_information.product_id')
            ->where('products.status', PRODUCT_ACTIVE)
            ->where('products.category_id', $categoryId)
            ->select(
                'products.id',
                'products.name',
                'products.category_id',
                'products.status',
                'products.created_by',
                'product_information.product_code',
                'product_information.sale_price',
                'product_information.origin_price',
                'product_information.stock',
                'products.description_list',
                'products.description_detail',
                'products.created_at',
                'products.updated_at',
            )
            ->with(['productImages' => function ($q) use ($url) {
                return $q->select(
                    'product_images.id',
                    'product_images.product_id',
                    DB::raw('CONCAT("' . $url . '", product_images.image) as image'),
                    'product_images.type',
                    'product_images.sort',
                    'product_images.status'
                );
            }])
            ->withCount('comments');

        if ($request->rating) {
            $query
                ->withAvg('comments as avg_rating', 'rating')
                ->having('avg_rating', '>=', $request->rating)
                ->groupBy('products.id');
        }

        if ($request->price_start && $request->price_end) {
            $query->whereRaw('COALESCE(product_information.sale_price, product_information.origin_price) BETWEEN ? AND ?', [$request->price_start, $request->price_end]);
        }

        if ($request->date_start && $request->date_end) {
            $query->whereBetween('products.updated_at', [$request->date_start, $request->date_end]);
        } else if ($request->date_start) {
            $query->where('products.updated_at', '>=', $request->date_start);
        } else if ($request->date_end) {
            $query->where('products.updated_at', '<=', $request->date_end);
        }

        if ($request->category_child) {
            $query->where('products.category_child_id', $request->category_child);
        }

        if (!empty($request->sort_price)) {
            $query->orderByRaw('CASE WHEN product_information.sale_price IS NOT NULL THEN sale_price ELSE origin_price END ' . $request->sort_price);
        }

        if ($request->newest) {
            $query->orderBy('products.updated_at', 'desc');
        }

        if ($request->popular) {
            // do something
        }

        return $query;
    }

    public function getListProductByStore($request, $storeId)
    {
        $url = getenv('IMAGEKIT_URL_ENDPOINT');
        $query = $this->_model
            ->join('product_information', 'products.id', '=', 'product_information.product_id')
            ->where('products.status', PRODUCT_ACTIVE)
            ->where('products.store_id', $storeId)
            ->select(
                'products.id',
                'products.name',
                'products.category_id',
                'products.status',
                'products.created_by',
                'product_information.product_code',
                'product_information.sale_price',
                'product_information.origin_price',
                'product_information.stock',
                'products.description_list',
                'products.description_detail',
                'products.created_at',
                'products.updated_at',
            )
            ->with(['productImages' => function ($q) use ($url) {
                return $q->select(
                    'product_images.id',
                    'product_images.product_id',
                    DB::raw('CONCAT("' . $url . '", product_images.image) as image'),
                    'product_images.type',
                    'product_images.sort',
                    'product_images.status'
                );
            }]);

        if (!empty($request->sort_price)) {
            $query->orderByRaw('CASE WHEN product_information.sale_price IS NOT NULL THEN sale_price ELSE origin_price END ' . $request->sort_price);
        }

        if ($request->newest) {
            $query->orderBy('products.updated_at', 'desc');
        }

        if ($request->popular) {
            // do something
        }

        return $query;
    }

    public function getInfoProduct($productId, $childMasterFieldId)
    {
        $query = $this->_model
            ->leftJoin('child_master_fields', 'child_master_fields.product_id', '=', 'products.id')
            ->join('product_information', 'products.id', '=', 'product_information.product_id')
            ->where('products.status', PRODUCT_ACTIVE)
            ->where('products.id', $productId)
            ->when($childMasterFieldId, function ($query, $childMasterFieldId) {
                return $query->where('child_master_fields.id', $childMasterFieldId);
            })
            ->when($childMasterFieldId, function ($query) {
                return $query->select(
                    'products.id',
                    'child_master_fields.sale_price',
                    'child_master_fields.origin_price',
                    'child_master_fields.stock',
                );
            }, function ($query) {
                return $query->select(
                    'products.id',
                    'product_information.product_code',
                    'product_information.sale_price',
                    'product_information.origin_price',
                    'product_information.stock',
                );
            })
            ->first();

        return $query;
    }
}

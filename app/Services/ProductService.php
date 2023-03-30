<?php

namespace App\Services;

use App\Repositories\CategoryChild\CategoryChildRepositoryInterface;
use App\Repositories\ChildMasterField\ChildMasterFieldRepositoryInterface;
use App\Repositories\MasterField\MasterFieldRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\ProductImage\ProductImageRepositoryInterface;
use App\Repositories\ProductInformation\ProductInformationRepositoryInterface;

class ProductService
{
    const PRODUCT_FOLDER = 'products';
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepositoryInterface;

    /**
     * @var ProductInformationRepositoryInterface
     */
    private $productInformationRepositoryInterface;

    /**
     * @var ProductImageRepositoryInterface
     */
    private $productImageRepositoryInterface;

    /**
     * @var ImageKitService
     */
    private $imageKitService;

    /**
     * @var MasterFieldRepositoryInterface
     */
    private $masterFieldRepositoryInterface;

    /**
     * @var ChildMasterFieldRepositoryInterface
     */
    private $childMasterFieldRepositoryInterface;

    /**
     * @var CategoryChildRepositoryInterface
     */
    private $categoryChildRepositoryInterface;

    public function __construct(
        ProductRepositoryInterface $productRepositoryInterface,
        ProductInformationRepositoryInterface $productInformationRepositoryInterface,
        ProductImageRepositoryInterface $productImageRepositoryInterface,
        ImageKitService $imageKitService,
        MasterFieldRepositoryInterface $masterFieldRepositoryInterface,
        ChildMasterFieldRepositoryInterface $childMasterFieldRepositoryInterface,
        CategoryChildRepositoryInterface $categoryChildRepositoryInterface
    )
    {
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->productInformationRepositoryInterface = $productInformationRepositoryInterface;
        $this->productImageRepositoryInterface = $productImageRepositoryInterface;
        $this->imageKitService = $imageKitService;
        $this->masterFieldRepositoryInterface = $masterFieldRepositoryInterface;
        $this->childMasterFieldRepositoryInterface = $childMasterFieldRepositoryInterface;
        $this->categoryChildRepositoryInterface = $categoryChildRepositoryInterface;
    }

    public function create($request, $user)
    {
        $checkExists = $this->productInformationRepositoryInterface->findOne('product_code', $request->product_code);

        if ($checkExists) {
            return _error(null, __('messages.product_code_exists'), HTTP_BAD_REQUEST);
        }

        if ($request->category_child_id) {
            $exitsCategory = $this->categoryChildRepositoryInterface->findOne('id', $request->category_child_id, $request->category_id);

            if (!$exitsCategory) {
                return _error(null, __('messages.category_child_not_exists'), HTTP_BAD_REQUEST);
            }
        }

        $newProduct = [
            'name' => $request->name,
            'created_by' => $user->id,
            'category_id' => $request->category_id,
            'category_child_id' => $request->category_child_id,
            'description_list' => $request->description_list,
            'description_detail' => $request->description_detail,
            'status' => $request->status,
        ];
        $product = $this->productRepositoryInterface->create($newProduct);

        $newProductInformation = [
            'product_id' => $product->id,
            'sale_price' => $request->sale_price,
            'origin_price' => $request->origin_price,
            'product_code' => $request->product_code,
            'stock' => $request->stock,
        ];
        $this->productInformationRepositoryInterface->create($newProductInformation);

        if (isset($request->images)) {
            $images = [];
            foreach ($request->images as $image) {
                $file = $image['image'];
                $fileName = $file->getClientOriginalName();
                $options = [
                    'folder' => self::PRODUCT_FOLDER,
                ];
                $uploadFile = $this->imageKitService->upload($file, $fileName, $options);

                $images[] = [
                    'product_id' => $product->id,
                    'image' => $uploadFile['filePath'],
                    'file_id' => $uploadFile['fileId'],
                    'type' => $image['type'],
                    'sort' => $image['sort'],
                    'status' => $image['status'],
                ];
            }
        }

        if (!empty($images)) {
            $this->productImageRepositoryInterface->insert($images);
        }

        if (isset($request->master_fields)) {
            foreach ($request->master_fields as $masterField) {
                $parentParams = [
                    'name' => $masterField['name'],
                    'product_id' => $product->id,
                ];

                $field = $this->masterFieldRepositoryInterface->create($parentParams);

                if (isset($masterField['childs'])) {
                    $childParams = [];
                    foreach ($masterField['childs'] as $child) {
                        $childParams[] = [
                            'name' => $child['name'],
                            'product_id' => $product->id,
                            'master_field_id' => $field->id,
                            'sale_price' => $child['sale_price'],
                            'origin_price' => $child['origin_price'],
                            'stock' => $child['stock'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    $this->childMasterFieldRepositoryInterface->insert($childParams);
                }
            }
        }
        
        return _success(null, __('messages.create_success'), HTTP_SUCCESS);
    }

    public function update($request, $user, $id)
    {
        $oldProduct = $this->productRepositoryInterface->find($id);
        if (!$oldProduct) {
            return _error(null, __('messages.product_not_found'), HTTP_NOT_FOUND);
        }

        $checkExists = $this->productInformationRepositoryInterface->checkExists('product_code', $request->product_code, $id);
        if ($checkExists) {
            return _error(null, __('messages.product_code_exists'), HTTP_BAD_REQUEST);
        }

        if ($request->category_child_id) {
            $exitsCategory = $this->categoryChildRepositoryInterface->findOne('id', $request->category_child_id, $request->category_id);

            if (!$exitsCategory) {
                return _error(null, __('messages.category_child_not_exists'), HTTP_BAD_REQUEST);
            }
        }

        $newProduct = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'category_child_id' => $request->category_child_id,
            'description_list' => $request->description_list,
            'description_detail' => $request->description_detail,
            'status' => $request->status,
            'created_by' => $user->id,
        ];
        $product = $this->productRepositoryInterface->update($id, $newProduct);

        $newProductInformation = [
            'product_id' => $product->id,
            'sale_price' => $request->sale_price,
            'origin_price' => $request->origin_price,
            'product_code' => $request->product_code,
            'stock' => $request->stock,
        ];
        $this->productInformationRepositoryInterface->update($id, $newProductInformation);

        if (isset($request->images)) {

            $this->deleteOldImages($id);

            $images = [];
            foreach ($request->images as $image) {
                $file = $image['image'];
                $fileName = $file->getClientOriginalName();
                $options = [
                    'folder' => self::PRODUCT_FOLDER,
                ];
                $uploadFile = $this->imageKitService->upload($file, $fileName, $options);

                $images[] = [
                    'product_id' => $product->id,
                    'image' => $uploadFile['filePath'],
                    'file_id' => $uploadFile['fileId'],
                    'type' => $image['type'],
                    'sort' => $image['sort'],
                    'status' => $image['status'],
                ];
            }

            if (!empty($images)) {
                $this->productImageRepositoryInterface->insert($images);
            }
        } else {
            $this->deleteOldImages($id);
        }

        if (isset($request->master_fields)) {
            foreach ($request->master_fields as $masterField) {

                if ($masterField['is_delete'] ?? false) {
                    if ($masterField['is_delete'] == IS_DELETE) {
                        $this->masterFieldRepositoryInterface->delete($masterField['id']);
                    }
    
                    if ($masterField['is_delete'] == IS_ADD) {
                        $parentParams = [
                            'name' => $masterField['name'],
                            'product_id' => $product->id,
                        ];
    
                        $field = $this->masterFieldRepositoryInterface->create($parentParams);
    
                        if (isset($masterField['childs'])) {
                            $childParams = [];
                            foreach ($masterField['childs'] as $child) {
                                $childParams[] = [
                                    'name' => $child['name'],
                                    'product_id' => $product->id,
                                    'master_field_id' => $field->id,
                                    'sale_price' => $child['sale_price'],
                                    'origin_price' => $child['origin_price'],
                                    'stock' => $child['stock'],
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }
                            $this->childMasterFieldRepositoryInterface->insert($childParams);
                        }
                    }
    
                    if ($masterField['is_delete'] == IS_UPDATE) {
    
                        $this->masterFieldRepositoryInterface->update($masterField['id'], ['name' => $masterField['name']]);
    
                        if (isset($masterField['childs'])) {
                            $childParams = [];
                            foreach ($masterField['childs'] as $child) {
                                if ($child['is_delete'] ?? false) {
                                    if ($child['is_delete'] == IS_DELETE) {
                                        $this->childMasterFieldRepositoryInterface->delete($child['id']);
                                    }
        
                                    if ($child['is_delete'] == IS_ADD) {
                                        $childParams = [
                                            'name' => $child['name'],
                                            'product_id' => $product->id,
                                            'master_field_id' => $masterField['id'],
                                            'sale_price' => $child['sale_price'],
                                            'origin_price' => $child['origin_price'],
                                            'stock' => $child['stock'],
                                            'created_at' => now(),
                                            'updated_at' => now(),
                                        ];
        
                                        $this->childMasterFieldRepositoryInterface->create($childParams);
                                    }
        
                                    if ($child['is_delete'] == IS_UPDATE) {
                                        $updateParams = [
                                            'name' => $child['name'],
                                            'sale_price' => $child['sale_price'],
                                            'origin_price' => $child['origin_price'],
                                            'stock' => $child['stock'],
                                        ];
        
                                        $this->childMasterFieldRepositoryInterface->update($child['id'], $updateParams);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $product = $this->productRepositoryInterface->find($id);
            if (!empty($product->masterFields)) {
                $product->masterFields()->delete();
            }
        }

        return _success(null, __('messages.update_success'), HTTP_SUCCESS);

        // Delete product in cart when change status to inactive
    }

    public function deleteOldImages($productId)
    {
        $product = $this->productRepositoryInterface->find($productId);

        if (!empty($product->productImages)) {
            foreach ($product->productImages as $productImage) {
                $this->imageKitService->delete($productImage->file_id);
            }

            $product->productImages()->delete();
        }
    }

    public function delete($id)
    {
        $product = $this->productRepositoryInterface->find($id);
        if (!$product) {
            return _error(null, __('messages.product_not_found'), HTTP_BAD_REQUEST);
        }
        
        $this->productInformationRepositoryInterface->findOne('product_id', $id)->delete();

        if (!empty($product->productImages)) {
            foreach ($product->productImages as $productImage) {
                $this->imageKitService->delete($productImage->file_id);
            }

            $product->productImages()->delete();
        }

        $product->delete();
        // Delete product in cart when delete product

        return _success(null, __('messages.delete_success'), HTTP_SUCCESS);
    }

    public function list($request)
    {
        $limit = $request->limit ?? LIMIT;
        $page = $request->page ?? PAGE;

        $products = $this->productRepositoryInterface->getListProduct($request)->paginate($limit, $page);

        return [
            'products' => $products->items(),
            'total' => $products->total(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage(),
        ];
    }

    public function show($id)
    {
        $product = $this->productRepositoryInterface->find($id);
        if (!$product) {
            return _error(null, __('messages.product_not_found'), HTTP_BAD_REQUEST);
        }

        $product = $this->productRepositoryInterface->detail($id);

        return _success($product, __('messages.success'), HTTP_SUCCESS);
    }

}
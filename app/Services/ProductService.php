<?php

namespace App\Services;

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

    public function __construct(
        ProductRepositoryInterface $productRepositoryInterface,
        ProductInformationRepositoryInterface $productInformationRepositoryInterface,
        ProductImageRepositoryInterface $productImageRepositoryInterface,
        ImageKitService $imageKitService
    )
    {
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->productInformationRepositoryInterface = $productInformationRepositoryInterface;
        $this->productImageRepositoryInterface = $productImageRepositoryInterface;
        $this->imageKitService = $imageKitService;
    }

    public function create($request, $user)
    {
        $checkExists = $this->productInformationRepositoryInterface->findOne('product_code', $request->product_code);

        if ($checkExists) {
            return _error(null, __('messages.product_code_exists'), HTTP_BAD_REQUEST);
        }

        $newProduct = [
            'name' => $request->name,
            'created_by' => $user->id,
            'category_id' => $request->category_id,
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
        $productInformation = $this->productInformationRepositoryInterface->create($newProductInformation);

        if (isset($request->images)) {
            $images = [];
            foreach ($request->images as $index => $image) {
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
        $productImages = $this->productImageRepositoryInterface->insert($images);
        
        return _success([
            'product' => $product,
            'productInformation' => $productInformation,
            'productImages' => $productImages,
        ], __('messages.create_success'), HTTP_SUCCESS);
    }

    public function update($request, $user, $id)
    {
        $newProduct = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description_list' => $request->description_list,
            'description_detail' => $request->description_detail,
            'status' => $request->status,
        ];
        $product = $this->productRepositoryInterface->update($newProduct, $id);

        $newProductInformation = [
            'product_id' => $product->id,
            'sale_price' => $request->sale_price,
            'origin_price' => $request->origin_price,
            'product_code' => $request->product_code,
            'stock' => $request->stock,
        ];
        $productInformation = $this->productInformationRepositoryInterface->update($newProductInformation, $id);

        if (isset($request->images)) {
            $images = [];
            foreach ($request->images as $index => $image) {
                $file = $image['image'];
                $fileName = $file->getClientOriginalName();
                $options = [
                    'folder' => self::PRODUCT_FOLDER,
                ];
                $uploadFile = $this->imageKitService->delete($file, $fileName, $options);

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

        // Delete product in cart when change status to inactive
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
      
    }

    public function show($id)
    {
        
    }

}
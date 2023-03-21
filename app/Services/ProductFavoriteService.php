<?php

namespace App\Services;

use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\ProductFavorite\ProductFavoriteRepositoryInterface;

class ProductFavoriteService
{
    /**
     * @var ProductFavoriteRepositoryInterface
     */
    private $productFavoriteRepositoryInterface;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        ProductFavoriteRepositoryInterface $productFavoriteRepositoryInterface,
        ProductRepositoryInterface $productRepository
    ) {
        $this->productFavoriteRepositoryInterface = $productFavoriteRepositoryInterface;
        $this->productRepository = $productRepository;
    }

    public function create($request, $user)
    {
        $product = $this->productRepository->find($request->product_id);

        if (!$product) {
            return _error(null, __('messages.product_not_found'), HTTP_NOT_FOUND);
        }

        $newProductFavorite = [
            'product_id' => $request->product_id,
            'user_id' => $user->id,
            'status' => PRODUCT_FAVORITE_ACTIVE
        ];

        $productFavorite = $this->productFavoriteRepositoryInterface->create($newProductFavorite);

        if (!$productFavorite) {
            return _error(null, __('messages.create_error'), HTTP_BAD_REQUEST);
        }

        return _success(null, __('messages.create_success'), HTTP_SUCCESS);
    }

    public function delete($id)
    {
        $productFavorite = $this->productFavoriteRepositoryInterface->find($id);

        if (!$productFavorite) {
            return _error(null, __('messages.not_found'), HTTP_NOT_FOUND);
        }

        $this->productFavoriteRepositoryInterface->delete($id);

        return _success(null, __('messages.delete_success'), HTTP_SUCCESS);
    }


    public function list($user)
    {
        $productFavorites = $this->productFavoriteRepositoryInterface->list($user);

        return _success($productFavorites, __('messages.success'), HTTP_SUCCESS);
    }
}

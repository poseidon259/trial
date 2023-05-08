<?php

namespace App\Services;

use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\ChildMasterField\ChildMasterFieldRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductRepositoryInterface;

class CartService
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepositoryInterface;

    /**
     * @var CartItemRepositoryInterface
     */
    private $cartItemRepositoryInterface;


    /**
     * @var ChildMasterFieldRepositoryInterface
     */
    private $childMasterFieldRepositoryInterface;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepositoryInterface;

    public function __construct(
        CartRepositoryInterface             $cartRepositoryInterface,
        CartItemRepositoryInterface         $cartItemRepositoryInterface,
        ChildMasterFieldRepositoryInterface $childMasterFieldRepositoryInterface,
        ProductRepositoryInterface          $productRepositoryInterface
    )
    {
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->cartItemRepositoryInterface = $cartItemRepositoryInterface;
        $this->childMasterFieldRepositoryInterface = $childMasterFieldRepositoryInterface;
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    public function addToCart($request, $user)
    {
        $cart = $this->cartRepositoryInterface->findOne('user_id', $user->id);

        if (!$cart) {
            $cart = $this->cartRepositoryInterface->create(['user_id' => $user->id]);
        }

        $params = [
            'product_id' => $request->product_id,
            'quantity' => $request->quantity
        ];

        $childMasterField = $this->childMasterFieldRepositoryInterface->findOne('product_id', $request->product_id);

        if ($childMasterField && is_null($request->child_master_field_id)) {
            return _error(null, __('messages.select_master_field'), HTTP_BAD_REQUEST);
        }

        if (isset($request->child_master_field_id)) {
            $params['child_master_field_id'] = $request->child_master_field_id;

            $checkChildMasterField = $this->childMasterFieldRepositoryInterface->getField($request->product_id, $request->child_master_field_id);

            if (!$checkChildMasterField) {
                return _error(null, __('messages.update_cart_error'), HTTP_BAD_REQUEST);
            }
        }

        $existingItem = $this->cartItemRepositoryInterface->getItem($request->product_id, $request->child_master_field_id, $cart->id);
        $productInfo = $this->productRepositoryInterface->getInfoProduct($request->product_id, $request->child_master_field_id);

        if ($existingItem) {
            // Update the quantity of the existing ite
            $quantityUpdated = $existingItem->quantity + $request->quantity;

            if ($quantityUpdated > $productInfo->stock) {
                return _error(null, __('messages.over_quantity_in_stock'), HTTP_BAD_REQUEST);
            }

            $existingItem->update(['quantity' => $quantityUpdated]);
        } else {

            if ($request->quantity > $productInfo->stock) {
                return _error(null, __('messages.over_quantity_in_stock'), HTTP_BAD_REQUEST);
            }
            $cart->cartItems()->create($params);
        }

        return _success(null, __('messages.update_cart_success'), HTTP_SUCCESS);
    }

    public function update($request, $user)
    {
        $cart = $this->cartRepositoryInterface->findOne('user_id', $user->id);

        if (!$cart) {
            $cart = $this->cartRepositoryInterface->create(['user_id' => $user->id]);
        }

        if (isset($request->items)) {
            $cart->cartItems()->delete();

            $cartItems = [];
            foreach ($request->items as $item) {
                $cartItems[] = [
                    'cart_id' => $cart->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ];
            }

            $cart->cartItems()->createMany($cartItems);
        } else {
            $cart->cartItems()->delete();
        }

        return _success(null, __('messages.update_cart_success'), HTTP_SUCCESS);
    }

    public function getMyCart($user)
    {
        $cart = $this->cartRepositoryInterface->findOne('user_id', $user->id);

        if (!$cart || !$cart->cartItems->count()) {
            return _error(null, __('messages.cart_null'), HTTP_BAD_REQUEST);
        }

        $cart = $this->cartRepositoryInterface->getCart($user->id);
        $cart['shipping_fee'] = DELIVERY_FEE;

        return _success($cart, __('messages.success'), HTTP_SUCCESS);
    }

    public function updateQuantity($request, $user)
    {
        $cart = $this->cartRepositoryInterface->findOne('user_id', $user->id);

        if (!$cart) {
            return _error(null, __('messages.cart_not_exists'), HTTP_BAD_REQUEST);
        }

        $childMasterField = $this->childMasterFieldRepositoryInterface->findOne('product_id', $request->product_id);

        if ($childMasterField && is_null($request->child_master_field_id)) {
            return _error(null, __('messages.select_master_field'), HTTP_BAD_REQUEST);
        }

        if (isset($request->child_master_field_id)) {
            $checkChildMasterField = $this->childMasterFieldRepositoryInterface->getField($request->product_id, $request->child_master_field_id);

            if (!$checkChildMasterField) {
                return _error(null, __('messages.update_cart_error'), HTTP_BAD_REQUEST);
            }
        }

        $existingItem = $this->cartItemRepositoryInterface->getItem($request->product_id, $request->child_master_field_id, $cart->id);
        $productInfo = $this->productRepositoryInterface->getInfoProduct($request->product_id, $request->child_master_field_id);

        if (!$existingItem) {
            return _error(null, __('messages.update_cart_error'), HTTP_BAD_REQUEST);
        }

        if ($request->quantity > $productInfo->stock) {
            return _error(null, __('messages.over_quantity_in_stock'), HTTP_BAD_REQUEST);
        }

        $existingItem->update(['quantity' => $request->quantity]);

        return _success(null, __('messages.update_cart_success'), HTTP_SUCCESS);
    }

    public function deleteProductInCart($request, $user)
    {
        $cart = $this->cartRepositoryInterface->findOne('user_id', $user->id);

        if (!$cart) {
            return _error(null, __('messages.cart_not_exists'), HTTP_BAD_REQUEST);
        }

        if (isset($request->child_master_field_id)) {

            $checkChildMasterField = $this->childMasterFieldRepositoryInterface->getField($request->product_id, $request->child_master_field_id);

            if (!$checkChildMasterField) {
                return _error(null, __('messages.update_cart_error'), HTTP_BAD_REQUEST);
            }
        }

        $existingItem = $this->cartItemRepositoryInterface->getItem($request->product_id, $request->child_master_field_id, $cart->id);

        if (!$existingItem) {
            return _error(null, __('messages.update_cart_error'), HTTP_BAD_REQUEST);
        }

        $existingItem->delete();

        return _success(null, __('messages.update_cart_success'), HTTP_SUCCESS);
    }

    public function getItemInCart($request, $user)
    {
        $cart = $this->cartRepositoryInterface->findOne('user_id', $user->id);

        if (!$cart) {
            return _error(null, __('messages.cart_not_exists'), HTTP_BAD_REQUEST);
        }

        $items = $this->cartRepositoryInterface->getItemInCart($request, $user->id);
        $items['shipping_fee'] = DELIVERY_FEE;

        return _success($items, __('messages.success'), HTTP_SUCCESS);
    }
}

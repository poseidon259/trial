<?php

namespace App\Services;

use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\ChildMasterField\ChildMasterFieldRepositoryInterface;

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

    public function __construct(
        CartRepositoryInterface     $cartRepositoryInterface,
        CartItemRepositoryInterface $cartItemRepositoryInterface,
        ChildMasterFieldRepositoryInterface $childMasterFieldRepositoryInterface
    )
    {
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->cartItemRepositoryInterface = $cartItemRepositoryInterface;
        $this->childMasterFieldRepositoryInterface  = $childMasterFieldRepositoryInterface;
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

        if (isset($request->child_master_field_id)) {
            $params['child_master_field_id'] = $request->child_master_field_id;

            $checkChildMasterField = $this->childMasterFieldRepositoryInterface->getField($request->product_id, $request->child_master_field_id);

            if (!$checkChildMasterField) {
                return _error(null, __('messages.update_cart_error'), HTTP_BAD_REQUEST);
            }
        }


        $existingItem = $this->cartItemRepositoryInterface->getItem($request->product_id, $request->child_master_field_id);

        if ($existingItem) {
            // Update the quantity of the existing item
            $quantityUpdated = $existingItem->quantity + $request->quantity;
            $existingItem->update(['quantity' => $quantityUpdated]);
        } else {
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

        return _success($cart, __('messages.success'), HTTP_SUCCESS);
    }
}

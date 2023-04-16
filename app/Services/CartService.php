<?php

namespace App\Services;

use App\Repositories\Cart\CartRepositoryInterface;

class CartService
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    public function __construct(
        CartRepositoryInterface $cartRepository
    )
    {
        $this->cartRepository = $cartRepository;
    }

    public function addToCart($request, $user)
    {
        $cart = $this->cartRepository->findOne('user_id', $user->id);

        if (!$cart) {
            $cart = $this->cartRepository->create(['user_id' => $user->id]);
        }

        $params = [
            'product_id' => $request->product_id,
            'quantity' => $request->quantity
        ];

        if ($request->child_master_field_id) {
            $params[] = [
                'child_master_field_id' => $request->child_master_field_id
            ];
        }

        $cart->cartItems()->create($params);

        return _success(null, __('messages.update_cart_success'), HTTP_SUCCESS);
    }

    public function update($request, $user)
    {
        $cart = $this->cartRepository->findOne('user_id', $user->id);

        if (!$cart) {
            $cart = $this->cartRepository->create(['user_id' => $user->id]);
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

    public function show($user)
    {
        $cart = $this->cartRepository->findOne('user_id', $user->id);

        if (!$cart || !$cart->cartItems->count()) {
            return _error(null, __('messages.cart_null'), HTTP_BAD_REQUEST);
        }

        $cart = $this->cartRepository->getCart($user->id);

        return _success($cart, __('messages.success'), HTTP_SUCCESS);
    }
}

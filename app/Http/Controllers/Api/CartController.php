<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCartRequest;
use App\Services\CartService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * @var CartService
     */
    private $cartService;

    public function __construct(
        CartService $cartService
    ) {
        $this->cartService = $cartService;
    }

    public function update(UpdateCartRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $cart = $this->cartService->update($request, $user);
            DB::commit();
            return $cart;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function show()
    {
        try {
            $user = Auth::user();
            $cart = $this->cartService->show($user);
            return $cart;
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }
}

<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BannerStoreController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryChildController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductFavoriteController;
use App\Http\Controllers\Api\UserAddressController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'api\v1'], function () {
    Route::get('test', function () {
        return 'test';
    });
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('verify_account', [AuthController::class, 'verifyAccount']);
    Route::get('resend_email_verify_account', [AuthController::class, 'resendEmailVerifyAccount'])->middleware('throttle:5,1');
    Route::get('send_email_reset_password', [AuthController::class, 'sendEmailResetPassword'])->middleware('throttle:5,1');
    Route::put('update_password', [AuthController::class, 'updatePassword']);
    Route::get('list_product', [ProductController::class, 'getListProductPublic']);
    Route::get('list_category', [CategoryController::class, 'list']);
    Route::get('list_banner', [BannerController::class, 'getListBannerPublic']);
    Route::get('client/store/{id}/banners', [BannerStoreController::class, 'getListBannerStorePublic']);
    Route::get('client/product/{id}', [ProductController::class, 'detailProductPublic']);
    Route::get('client/product/{id}/comments', [CommentController::class, 'getListCommentPublic']);
    Route::get('client/category/{id}', [CategoryController::class, 'getCategoryPublic']);
    Route::get('client/category/{id}/products', [ProductController::class, 'getListProductByCategory']);
    Route::get('client/stores/homepage', [StoreController::class, 'getStoreHomepage']);
    Route::get('client/store/{id}', [StoreController::class, 'detailStorePublic']);
    Route::get('client/store/{id}/products', [ProductController::class, 'getListProductByStore']);

    Route::group(['middleware' => 'auth:api'], function () {
        // client
        Route::post('client/product/{id}/comment/create', [CommentController::class, 'createCommentPublic']);
        Route::post('client/add_to_cart', [CartController::class, 'addToCart']);
        Route::get('client/get_my_cart', [CartController::class, 'getMyCart']);
        Route::post('client/update_quantity_cart', [CartController::class, 'updateQuantity']);
        Route::delete('client/delete_product_in_cart', [CartController::class, 'deleteProductInCart']);

        //
        Route::get('profile', [UserController::class, 'profile']);
        Route::post('update_profile', [UserController::class, 'updateProfile']);
        Route::put('change_password', [UserController::class, 'updatePassword']);

        Route::prefix('user_address')->group(function () {
            Route::get('list', [UserAddressController::class, 'list']);
            Route::get('show/{addressId}', [UserAddressController::class, 'show']);
            Route::post('create', [UserAddressController::class, 'create']);
            Route::put('update/{addressId}', [UserAddressController::class, 'update']);
            Route::delete('delete/{addressId}', [UserAddressController::class, 'delete']);
        });

        Route::prefix('user')->group(function () {
            Route::get('show/{id}', [UserController::class, 'show'])->middleware('permission:admin');
            Route::post('create', [UserController::class, 'create'])->middleware('permission:admin');
            Route::post('update/{id}', [UserController::class, 'update'])->middleware('permission:admin');
            Route::delete('delete/{id}', [UserController::class, 'delete'])->middleware('permission:admin');
            Route::get('list', [UserController::class, 'list'])->middleware('permission:admin');
            Route::put('{id}/change_status', [UserController::class, 'changeStatus'])->middleware('permission:admin');
        });

        Route::prefix('store')->group(function () {
            Route::get('list', [StoreController::class, 'list'])->middleware('permission:admin');
            Route::get('show/{id}', [StoreController::class, 'show'])->middleware('permission:admin');
            Route::post('create', [StoreController::class, 'create'])->middleware('permission:admin');
            Route::post('update/{id}', [StoreController::class, 'update'])->middleware('permission:admin');
            Route::delete('delete/{id}', [StoreController::class, 'delete'])->middleware('permission:admin');
        });

        Route::prefix('category')->group(function () {
            Route::get('show/{id}', [CategoryController::class, 'show']);
            Route::post('create', [CategoryController::class, 'create']);
            Route::post('update/{id}', [CategoryController::class, 'update']);
            Route::delete('delete/{id}', [CategoryController::class, 'delete']);

            Route::prefix('{categoryId}/child')->group(function () {
                Route::post('create', [CategoryChildController::class, 'create']);
                Route::put('update/{id}', [CategoryChildController::class, 'update']);
                Route::delete('delete/{id}', [CategoryChildController::class, 'delete']);
            });
        });

        Route::prefix('product')->group(function () {
            Route::get('list', [ProductController::class, 'list']);
            Route::get('show/{id}', [ProductController::class, 'show']);
            Route::post('create', [ProductController::class, 'create']);
            Route::post('update/{id}', [ProductController::class, 'update']);
            Route::delete('delete/{id}', [ProductController::class, 'delete']);

            Route::prefix('{productId}/comment')->group(function () {
                Route::get('list', [CommentController::class, 'list']);
                Route::get('show/{commentId}', [CommentController::class, 'show']);
                Route::post('create', [CommentController::class, 'create']);
                Route::post('update/{commentId}', [CommentController::class, 'update']);
                Route::delete('delete/{commentId}', [CommentController::class, 'delete']);
            });
        });

        Route::prefix('product_favorite')->group(function () {
            Route::get('list', [ProductFavoriteController::class, 'list']);
            Route::post('create', [ProductFavoriteController::class, 'create']);
            Route::delete('delete/{id}', [ProductFavoriteController::class, 'delete']);
        });

        Route::prefix('banner')->group(function () {
            Route::get('list', [BannerController::class, 'list']);
            Route::post('update', [BannerController::class, 'update']);
        });

        Route::prefix('banner_store')->group(function () {
            Route::get('{id}/list', [BannerStoreController::class, 'list']);
            Route::post('{id}/update', [BannerStoreController::class, 'update']);
        });

        Route::prefix('order')->group(function () {
            Route::get('list', [OrderController::class, 'list']);
            Route::get('show/{id}', [OrderController::class, 'show']);
            Route::post('create', [OrderController::class, 'create']);
            Route::delete('delete/{id}', [OrderController::class, 'delete']);
        });
    });
});

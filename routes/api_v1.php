<?php

use App\Http\Controllers\Api\AuthController;
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
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('verify_account', [AuthController::class, 'verifyAccount']);
    Route::get('resend_email_verify_account', [AuthController::class, 'resendEmailVerifyAccount'])->middleware('throttle:5,1');
    Route::get('send_email_reset_password', [AuthController::class, 'sendEmailResetPassword'])->middleware('throttle:5,1');
    Route::put('update_password', [AuthController::class, 'updatePassword']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::prefix('user')->group(function () {
            Route::put('update_profile', [UserController::class, 'updateProfile']);
            Route::get('show/{id}', [UserController::class, 'show']);
            Route::post('create', [UserController::class, 'create']);
            Route::put('update/{id}', [UserController::class, 'update']);
            Route::delete('delete/{id}', [UserController::class, 'delete']);
            Route::get('list', [UserController::class, 'list']);
            Route::put('{id}/change_status', [UserController::class, 'changeStatus']);
            Route::put('change_password', [UserController::class, 'updatePassword']);
        });

        Route::prefix('store')->group(function () {
            Route::get('list', [StoreController::class, 'list']);
            Route::get('show/{id}', [StoreController::class, 'show']);
            Route::post('create', [StoreController::class, 'create']);
            Route::put('update/{id}', [StoreController::class, 'update']);
            Route::delete('delete/{id}', [StoreController::class, 'delete']);
        });
    });
    
});

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResendEmailVerifyAccountRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\VerifyAccountRequest;
use App\Services\AuthService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private $authService;

    public function __construct(
        AuthService $authService,
    )
    {
        $this->authService = $authService;
    }
        
    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(LoginRequest $request) {
        try {
            return $this->authService->login($request);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }
    
    /**
     * register
     *
     * @param  mixed $request
     * @return void
     */
    public function register(RegisterRequest $request) {
        DB::beginTransaction();
        try {
            $user = $this->authService->register($request);
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }
    
    /**
     * verifyAccount
     *
     * @param  mixed $request
     * @return void
     */
    public function verifyAccount(VerifyAccountRequest $request) {
        DB::beginTransaction();
        try {
            $user = $this->authService->verifyAccount($request);
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function resendEmailVerifyAccount(ResendEmailVerifyAccountRequest $request) {
        try {
            return $this->authService->resendEmailVerifyAccount($request);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function sendEmailResetPassword(ResetPasswordRequest $request) {
        try {
            return $this->authService->sendEmailResetPassword($request);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function updatePassword(UpdatePasswordRequest $request) {
        DB::beginTransaction();
        try {
            $user =  $this->authService->updatePassword($request);
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }
}

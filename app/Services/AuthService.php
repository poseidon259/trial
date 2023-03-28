<?php

namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class AuthService
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepositoryInterface;

    /**
     * @var MailService
     */
    private $mailService;

    public function __construct(
        UserRepositoryInterface $userRepositoryInterface,
        MailService $mailService
    )
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
        $this->mailService = $mailService;
    }

    public function login($request)
    {
        if ($request->type == LOGIN_MAIL) {
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];

            if (Auth::attempt($credentials) == false) {
                return _error(null, __('messages.wrong_password_or_email'), HTTP_BAD_REQUEST);
            }
            
            $user = Auth::user();

            if (!$user) {
                return _error(null, __('messages.wrong_password_or_email'), HTTP_BAD_REQUEST);
            }

            if (is_null($user->email_verified_at)) {
                return _error(null, __('messages.email_not_verified'), HTTP_BAD_REQUEST);
            }

            if ($user->status == INACTIVE) {
                return _error(null, __('messages.user_inactive'), HTTP_BAD_REQUEST);
            }

            if ($user->role_id != $request->system) {
                return _error(null, __('messages.no_permission'), HTTP_FORBIDDEN);
            }

            $tokenResult = $user->createToken('Personal Access Token');
            $data = [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
            ];

            return _success($data, __('messages.login_success'), HTTP_SUCCESS);
        }

        if ($request->type == LOGIN_PHONE_NUMBER) {
            $credentials = [
                'phone_number' => $request->phone_number,
                'password' => $request->password,
            ];

            if (Auth::attempt($credentials) == false) {
                return _error(null, __('messages.wrong_password_or_email'), HTTP_BAD_REQUEST);
            }

            $user = Auth::user();

            if (!$user) {
                return _error(null, __('messages.wrong_password_or_email'), HTTP_BAD_REQUEST);
            }

            if ($user->status == INACTIVE) {
                return _error(null, __('messages.user_inactive'), HTTP_BAD_REQUEST);
            }

            if ($user->role_id != $request->system) {
                return _error(null, __('messages.no_permission'), HTTP_FORBIDDEN);
            }

            $tokenResult = $user->createToken('Personal Access Token');
            $data = [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
            ];

            return _success($data, __('messages.login_success'), HTTP_SUCCESS);
        }

        if ($request->type == LOGIN_USER_NAME) {
            $credentials = [
                'user_name' => $request->user_name,
                'password' => $request->password,
            ];

            if (Auth::attempt($credentials) == false) {
                return _error(null, __('messages.wrong_password_or_email'), HTTP_BAD_REQUEST);
            }

            $user = Auth::user();

            if (!$user) {
                return _error(null, __('messages.wrong_password_or_email'), HTTP_BAD_REQUEST);
            }

            if ($user->status == INACTIVE) {
                return _error(null, __('messages.user_inactive'), HTTP_BAD_REQUEST);
            }

            if ($user->role_id != $request->system) {
                return _error(null, __('messages.no_permission'), HTTP_FORBIDDEN);
            }

            $tokenResult = $user->createToken('Personal Access Token');
            $data = [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
            ];

            return _success($data, __('messages.login_success'), HTTP_SUCCESS);
        }

        return _error(null, __('messages.no_permission'), HTTP_BAD_REQUEST);
    }

    public function register($request) {
        $checkEmailUser = $this->userRepositoryInterface->findOne('email', $request->email);

        if ($checkEmailUser) {
            return _error(null, __('messages.email_exists'), HTTP_BAD_REQUEST);
        }

        $user = $this->userRepositoryInterface->create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => ACTIVE,
            'role_id' => ROLE_USER,
        ]);

        if (!$user) {
            return _error(null, __('messages.created_fail'), HTTP_BAD_REQUEST);
        }
        
        $dataMail = [
            'name' => $request->first_name . ' ' . $request->last_name,
            'url' => env('BASE_URL') . '/verify_account?email=' . $request->email . '&id=' . $user->id,
        ];

        $tokenResult = $user->createToken('Personal Access Token');
        $data = [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
        ];

        if ($tokenResult) {
            $this->mailService->sendEmail(
                $request->email,
                $dataMail,
                __('messages.title_email_register'),
                'mail.account_register'
            );
        }

        return _success($data, __('messages.created_success'), HTTP_SUCCESS);
    }

    public function verifyAccount($request) {
        $user = $this->userRepositoryInterface->find($request->id);

        if (!$user) {
            return _error(null, __('messages.user_not_found'), HTTP_BAD_REQUEST);
        }

        if ($user->email_verified_at) {
            return _error(null, __('messages.user_verified'), HTTP_BAD_REQUEST);
        }

        $dateCreatedAccount = $user->updated_at;
        $dateCheck = $dateCreatedAccount->addminutes(15);
        $now = new DateTime();

        if ($dateCheck >= $now) {
            $user = $this->userRepositoryInterface->update($user->id, [
                'email_verified_at' => $now,
            ]);

            return _success(null, __('messages.verify_success'), HTTP_SUCCESS);
        }

        return _error(null, __('messages.verify_fail'), HTTP_BAD_REQUEST);
    }

    public function resendEmailVerifyAccount($request) {
        $user = $this->userRepositoryInterface->findOne('email', $request->email);
        
        if (!$user) {
            return _error(null, __('messages.user_not_found'), HTTP_BAD_REQUEST);
        }

        if ($user->email_verified_at) {
            return _error(null, __('messages.user_verified'), HTTP_BAD_REQUEST);
        }

        $now = new DateTime();
        $user =  $this->userRepositoryInterface->update($user->id, [
            'updated_at' => $now,
        ]);

        if (!$user) {
            return _error(null, __('messages.resend_email_fail'), HTTP_BAD_REQUEST);
        }

        $data = [
            'name' => $user->first_name . ' ' . $user->last_name,
            'url' => env('BASE_URL') . '/verify_account?email=' . $user->email . '&id=' . $user->id,
        ];

        $this->mailService->sendEmail(
            $request->email,
            $data,
            __('messages.title_resend_email_register'),
            'mail.account_register'
        );

        return _success(null, __('messages.resend_email_success'), HTTP_SUCCESS);
    }

    public function sendEmailResetPassword($request) {
        $checkExistsEmail = $this->userRepositoryInterface->findOne('email', $request->email);
        if (!$checkExistsEmail) {
            return _error(null, __('messages.email_not_exists'), HTTP_BAD_REQUEST);
        }

        if (is_null($checkExistsEmail->email_verified_at)) {
            return _error(null, __('messages.user_not_verified'), HTTP_BAD_REQUEST);
        }

        $now = new DateTime();
        $code = rand(100000, 999999);

        $user = $this->userRepositoryInterface->update($checkExistsEmail->id, [
            'updated_at' => $now,
        ]);

        if (!$user) {
            return _error(null, __('messages.send_email_reset_password_fail'), HTTP_BAD_REQUEST);
        }

        Redis::set($request->email, $code);

        $data = [
            'name' => $checkExistsEmail->first_name . ' ' . $checkExistsEmail->last_name,
            'code' => $code,
        ];

        $this->mailService->sendEmail(
            $request->email,
            $data,
            __('messages.title_email_reset_password'),
            'mail.reset_password'
        );

        return _success(null, __('messages.send_email_reset_password_success'), HTTP_SUCCESS);
    }

    public function updatePassword($request) {
        $checkExistsEmail = $this->userRepositoryInterface->findOne('email', $request->email);

        if (!$checkExistsEmail) {
            return _error(null, __('messages.user_not_found'), HTTP_BAD_REQUEST);
        }

        if (is_null($checkExistsEmail->email_verified_at)) {
            return _error(null, __('messages.user_not_verified'), HTTP_BAD_REQUEST);
        }

        $dateUpdatedAccount = $checkExistsEmail->updated_at;
        $dateCheck = $dateUpdatedAccount->addminutes(15);
        $now = new DateTime();

        if ($dateCheck < $now) {
            return _error(null, __('messages.code_expired'), HTTP_BAD_REQUEST);
        }

        $code = Redis::get($request->email);

        if ($code != $request->code) {
            return _error(null, __('messages.code_not_match'), HTTP_BAD_REQUEST);
        }

        $user = $this->userRepositoryInterface->update($checkExistsEmail->id, [
            'password' => Hash::make($request->new_password),
        ]);

        if (!$user) {
            return _error(null, __('messages.update_password_fail'), HTTP_BAD_REQUEST);
        }

        Redis::del($request->email);

        return _success(null, __('messages.update_password_success'), HTTP_SUCCESS);
    }
}
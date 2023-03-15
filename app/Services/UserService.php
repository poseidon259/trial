<?php

namespace App\Services;


use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    const IMAGEKIT_FOLDER = '/users';
    /**
     * @var UserRepositoryInterface
     */
    private $userRepositoryInterface;

    /**
     * @var MailService
     */
    private $mailService;

    /**
     * @var ImageKitService
     */
    private $imageKitService;

    public function __construct(
        UserRepositoryInterface $userRepositoryInterface,
        MailService $mailService,
        ImageKitService $imageKitService
    )
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
        $this->mailService = $mailService;
        $this->imageKitService = $imageKitService;
    }

    public function updateProfile($request, $user) {

        $params = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'postal_code' => $request->postal_code,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'ward_id' => $request->ward_id,
            'house_number' => $request->house_number,
            'phone_number' => $request->phone_number,
            'user_name' => $request->user_name,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'avatar' => $request->avatar,
        ];

        $newUser = $this->userRepositoryInterface->update($user->id, $params);

        if (!$newUser) {
            return _error(null, __('messages.update_error'), HTTP_BAD_REQUEST);
        }

        return _success($newUser, __('messages.update_success'), HTTP_SUCCESS);
    }

    public function show($id) {
        $user = $this->userRepositoryInterface->find($id);

        if (!$user) {
            return _error(null, __('messages.user_not_found'), HTTP_NOT_FOUND);
        }

        return _success($user, __('messages.success'), HTTP_SUCCESS);
    }

    public function create($request) {

        $checkExistsEmail = $this->userRepositoryInterface->findOne('email', $request->email);
        if ($checkExistsEmail) {
            return _error(null, __('messages.email_exists'), HTTP_BAD_REQUEST);
        }

        $checkExistsUserName = $this->userRepositoryInterface->findOne('user_name', $request->user_name);
        if ($checkExistsUserName) {
            return _error(null, __('messages.user_name_exists'), HTTP_BAD_REQUEST);
        }

        $checkExistsPhoneNumber = $this->userRepositoryInterface->findOne('phone_number', $request->phone_number);
        if ($checkExistsPhoneNumber) {
            return _error(null, __('messages.phone_number_exists'), HTTP_BAD_REQUEST);
        }

        $password = $request->password;
        if (!$request->password) {
            $password = Str::random(10);
        }

        $params = [
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'password' => Hash::make($password),
            'role_id' => $request->role_id,
            'postal_code' => $request->postal_code,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'ward_id' => $request->ward_id,
            'house_number' => $request->house_number,
            'phone_number' => $request->phone_number,
            'birthday' => $request->birthday,
            'avatar' => $request->avatar,
            'gender' => $request->gender,
            'user_name' => $request->user_name,
            'status' => $request->status,
        ];

        if (isset($request->avatar)) {
            $file = $request->avatar;
            $fileName = $file->getClientOriginalName();
            $options = [
                'folder' => self::IMAGEKIT_FOLDER,
            ];

            $uploadFile = $this->imageKitService->upload($file, $fileName, $options);
            $params['fileId'] = $uploadFile['fileId'];
            $params['avatar'] = $uploadFile['filePath'];
        }

        $user = $this->userRepositoryInterface->create($params);

        if (!$user) {
            return _error(null, __('messages.create_error'), HTTP_BAD_REQUEST);
        }

        $data = [
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'user_name' => $user->user_name,
            'phone_number' => $user->phone_number,
            'password' => $password,
            'url' => $request->role_id == ROLE_ADMIN ? env('ADMIN_URL') : env('STORE_URL')
        ];

        $this->mailService->sendEmail(
            $request->email,
            $data,
            __('messages.title_create_account'),
            'mail.create_user'
        );

        return _success($user, __('messages.create_success'), HTTP_SUCCESS);
    }
    
    public function update($request, $id) {

        $user = $this->userRepositoryInterface->find($id);

        if (!$user) {
            return _error(null, __('messages.user_not_found'), HTTP_NOT_FOUND);
        }

        $password = $request->password;
        if (!$request->password) {
            $password = Str::random(10);
        }

        $params = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'postal_code' => $request->postal_code,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'ward_id' => $request->ward_id,
            'house_number' => $request->house_number,
            'avatar' => $request->avatar,
            'birthday' => $request->birthday,
            'status' => $request->status,
            'gender' => $request->gender,
            'role_id' => $request->role_id,
            'password' => Hash::make($password),
        ];

        $user = $this->userRepositoryInterface->update($id, $params);

        if (!$user) {
            return _error(null, __('messages.update_error'), HTTP_BAD_REQUEST);
        }

        $data = [
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'user_name' => $user->user_name,
            'phone_number' => $user->phone_number,
            'password' => $password,
            'url' => $request->role_id == ROLE_ADMIN ? env('ADMIN_URL') : env('STORE_URL')
        ];

        $this->mailService->sendEmail(
            $user->email,
            $data,
            __('messages.title_update_account'),
            'mail.update_info_admin'
        );

        return _success($user, __('messages.update_success'), HTTP_SUCCESS);
    }

    public function delete($id) {
        $user = $this->userRepositoryInterface->find($id);

        if (!$user) {
            return _error(null, __('messages.user_not_found'), HTTP_NOT_FOUND);
        }


        $this->userRepositoryInterface->update($id, [
            'email' => $user->email . now(),
            'user_name' => $user->user_name . now(),
            'phone_number' => $user->phone_number . now(),
        ]);
        $user = $this->userRepositoryInterface->delete($id);

        if (!$user) {
            return _error(null, __('messages.delete_error'), HTTP_BAD_REQUEST);
        }

        return _success($user, __('messages.delete_success'), HTTP_SUCCESS);
    }

    public function list($request) {
        $limit = $request->limit ?? LIMIT;
        $page = $request->page ?? PAGE;

        $users = $this->userRepositoryInterface->g($request)->paginate($limit, $page);

        return [
            'users' => $users->items(),
            'total' => $users->total(),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'per_page' => $users->perPage(),
        ];
    }

    public function updatePassword($request, $user) {
        $oldPassword = $request->old_password;

        if (!Hash::check($oldPassword, $user->password)) {
            return _error(null, __('messages.old_password_not_correct'), HTTP_BAD_REQUEST);
        }

        $user = $this->userRepositoryInterface->update($user->id, [
            'password' => Hash::make($request->new_password)
        ]);

        $user->tokens()->delete();

        if (!$user) {
            return _error(null, __('messages.update_error'), HTTP_BAD_REQUEST);
        }

        return _success($user, __('messages.update_success'), HTTP_SUCCESS);
    }

    public function changeStatus($request, $id) {
        $user = $this->userRepositoryInterface->find($id);

        if (!$user) {
            return _error(null, __('messages.user_not_found'), HTTP_NOT_FOUND);
        }

        $user = $this->userRepositoryInterface->update($id, [
            'status' => $request->status
        ]);

        if (!$user) {
            return _error(null, __('messages.update_error'), HTTP_BAD_REQUEST);
        }

        return _success($user, __('messages.update_success'), HTTP_SUCCESS);
    }
}
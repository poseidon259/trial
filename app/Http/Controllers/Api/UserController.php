<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\GetListUserRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(
        UserService $userService,
    ) {
        $this->userService = $userService;
    }

    public function updateProfile(UpdateInfoRequest $request)
    {
        DB::beginTransaction();
        try {
            $userLogin = Auth::user();
            $user =  $this->userService->updateProfile($request, $userLogin);
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function show($id)
    {
        try {
            return $this->userService->show($id);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function create(CreateUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->create($request);
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->update($request, $id);
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->delete($id);
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function list(GetListUserRequest $request)
    {
        try {
            return $this->userService->list($request);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function updatePassword(UserUpdatePasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $userLogin = Auth::user();
            $user =  $this->userService->updatePassword($request, $userLogin);
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function changeStatus(ChangeStatusRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->changeStatus($request, $id);
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }
}

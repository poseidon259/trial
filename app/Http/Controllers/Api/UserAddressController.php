<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserAddressRequest;
use App\Http\Requests\UpdateUserAddressRequest;
use App\Services\UserAddressService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserAddressController extends Controller
{
    /**
     * @var UserAddressService
     */
    private $userAddressService;

    public function __construct(
        UserAddressService $userAddressService
    ) {
        $this->userAddressService = $userAddressService;
    }

    public function create(CreateUserAddressRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $userAddress = $this->userAddressService->create($request, $user);
            DB::commit();
            return $userAddress;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function update(UpdateUserAddressRequest $request, $addressId)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $userAddress = $this->userAddressService->update($request, $user, $addressId);
            DB::commit();
            return $userAddress;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function delete($userId, $id)
    {
        DB::beginTransaction();
        try {
            $userAddress = $this->userAddressService->delete($userId, $id);
            DB::commit();
            return $userAddress;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function show($id)
    {
        try {
            return $this->userAddressService->show($id);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function list()
    {
        try {
            $user = Auth::user();
            return $this->userAddressService->list($user);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }
}

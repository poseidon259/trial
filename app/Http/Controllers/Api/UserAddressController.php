<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserAddressRequest;
use App\Http\Requests\UpdateUserAddressRequest;
use App\Services\UserAddressService;
use Exception;
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

    public function create(CreateUserAddressRequest $request, $userId)
    {
        DB::beginTransaction();
        try {
            $userAddress = $this->userAddressService->create($request, $userId);
            DB::commit();
            return $userAddress;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function update(UpdateUserAddressRequest $request, $userId, $addressId) {
        DB::beginTransaction();
        try {
            $userAddress = $this->userAddressService->update($request, $userId, $addressId);
            DB::commit();
            return $userAddress;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function delete($userId, $id) {
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

    public function show($userId, $id) {
        try {
            $userAddress = $this->userAddressService->show($userId, $id);
            return $userAddress;
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function list($userId) {
        try {
            $userAddress = $this->userAddressService->list($userId);
            return $userAddress;
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }
}

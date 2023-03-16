<?php

namespace App\Services;

use App\Repositories\UserAddress\UserAddressRepositoryInterface;

class UserAddressService
{
    /**
     * @var UserAddressRepositoryInterface
     */
    private $userAddressRepositoryInterface;

    public function __construct(
        UserAddressRepositoryInterface $userAddressRepositoryInterface
    ) {
        $this->userAddressRepositoryInterface = $userAddressRepositoryInterface;
    }

    public function create($request, $userId)
    {
        $addresses = $this->userAddressRepositoryInterface->getListByUserId($userId);
        $count = count($addresses);
        if ($count >= LIMIT_ADDRESS) {
            return _error(null, __('messages.address_limit'), HTTP_BAD_REQUEST);
        }

        $params = [
            'user_id' => $userId,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'ward_id' => $request->ward_id,
            'house_number' => $request->house_number,
            'is_default' => $request->is_default
        ];

        $userAddress = $this->userAddressRepositoryInterface->create($params);

        if (!$userAddress) {
            return _error(null, __('messages.create_error'), HTTP_BAD_REQUEST);
        }

        if ($request->is_default == ADDRESS_DEFAULT) {
            $this->userAddressRepositoryInterface->updateDefault($userId, $userAddress->id);
        }

        return _success($userAddress, __('messages.create_success'), HTTP_SUCCESS);
    }

    public function update($request, $userId, $addressId)
    {
        $address = $this->userAddressRepositoryInterface->find($addressId);

        if (!$address) {
            return _error(null, __('messages.address_not_found'), HTTP_BAD_REQUEST);
        }

        $params = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'ward_id' => $request->ward_id,
            'house_number' => $request->house_number,
            'is_default' => $request->is_default
        ];

        $userAddress = $this->userAddressRepositoryInterface->update($addressId, $params);

        if (!$userAddress) {
            return _error(null, __('messages.update_error'), HTTP_BAD_REQUEST);
        }

        if ($request->is_default == ADDRESS_DEFAULT) {
            $this->userAddressRepositoryInterface->updateDefault($userId, $addressId);
        }

        return _success($userAddress, __('messages.update_success'), HTTP_SUCCESS);
    }

    public function delete($userId, $addressId)
    {
        $address = $this->userAddressRepositoryInterface->find($addressId);

        if (!$address) {
            return _error(null, __('messages.address_not_found'), HTTP_BAD_REQUEST);
        }

        if ($address->is_default == ADDRESS_DEFAULT) {
            return _error(null, __('messages.delete_default_address_error'), HTTP_BAD_REQUEST);
        }

        $userAddress = $this->userAddressRepositoryInterface->delete($addressId);

        if (!$userAddress) {
            return _error(null, __('messages.delete_error'), HTTP_BAD_REQUEST);
        }

        return _success($userAddress, __('messages.delete_success'), HTTP_SUCCESS);
    }

    public function show($userId, $addressId)
    {
        $address = $this->userAddressRepositoryInterface->find($addressId);

        if (!$address) {
            return _error(null, __('messages.address_not_found'), HTTP_BAD_REQUEST);
        }

        return _success($address, __('messages.success'), HTTP_SUCCESS);
    }

    public function list($userId)
    {
        $addresses = $this->userAddressRepositoryInterface->getListByUserId($userId);

        if (!$addresses) {
            return _error(null, __('messages.address_not_found'), HTTP_BAD_REQUEST);
        }

        return _success($addresses, __('messages.success'), HTTP_SUCCESS);
    }
}

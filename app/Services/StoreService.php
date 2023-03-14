<?php

namespace App\Services;

use App\Repositories\Store\StoreRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class StoreService
{
    /**
     * @var StoreRepositoryInterface
     */
    private $storeRepositoryInterface;

    /**
     * @var MailService
     */
    private $mailService;

    public function __construct(
        StoreRepositoryInterface $storeRepositoryInterface,
        MailService $mailService
    ) {
        $this->storeRepositoryInterface = $storeRepositoryInterface;
        $this->mailService = $mailService;
    }

    public function create($request)
    {
        $checkExistsEmail = $this->storeRepositoryInterface->findOne('email', $request->email);
        if ($checkExistsEmail) {
            return _error(null, __('messages.email_exists'), HTTP_BAD_REQUEST);
        }

        $checkExistsPhone = $this->storeRepositoryInterface->findOne('phone_number', $request->phone_number);
        if ($checkExistsPhone) {
            return _error(null, __('messages.phone_number_exists'), HTTP_BAD_REQUEST);
        }

        $params = [
            'email' => $request->email,
            'manager_name' => $request->manager_name,
            'phone_number' => $request->phone_number,
            'company_name' => $request->company_name,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'ward_id' => $request->ward_id,
            'house_number' => $request->house_number,
            'description_list' => $request->description_list,
            'description_detail' => $request->description_detail,
            'logo' => $request->logo,
            'background_image' => $request->background_image,
            'status' => $request->status,
        ];

        $store = $this->storeRepositoryInterface->create($params);

        if (!$store) {
            return _error(null, __('messages.create_error'), HTTP_BAD_REQUEST);
        }

        return _success($store, __('messages.create_success'), HTTP_SUCCESS);
    }

    public function update($request, $id)
    {
        $store = $this->storeRepositoryInterface->find($id);

        if (!$store) {
            return _error(null, __('messages.store_not_found'), HTTP_BAD_REQUEST);
        }

        $checkExistsEmail = $this->storeRepositoryInterface->checkExists('email', $request->email, $store->id);
        if ($checkExistsEmail) {
            return _error(null, __('messages.email_exists'), HTTP_BAD_REQUEST);
        }

        $checkExistsPhone = $this->storeRepositoryInterface->checkExists('phone_number', $request->phone_number, $store->id);
        if ($checkExistsPhone) {
            return _error(null, __('messages.phone_number_exists'), HTTP_BAD_REQUEST);
        }

        $params = [
            'email' => $request->email,
            'manager_name' => $request->manager_name,
            'company_name' => $request->company_name,
            'phone_number' => $request->phone_number,
            'postal_code' => $request->postal_code,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'ward_id' => $request->ward_id,
            'house_number' => $request->house_number,
            'description_list' => $request->description_list,
            'description_detail' => $request->description_detail,
            'logo' => $request->logo,
            'background_image' => $request->background_image,
            'status' => $request->status,
        ];

        $store = $this->storeRepositoryInterface->update($store, $params);

        if (!$store) {
            return _error(null, __('messages.update_error'), HTTP_BAD_REQUEST);
        }

        return _success($store, __('messages.update_success'), HTTP_SUCCESS);
    }

    public function delete($id) {
        $store = $this->storeRepositoryInterface->find($id);

        if (!$store) {
            return _error(null, __('messages.store_not_found'), HTTP_BAD_REQUEST);
        }

        $store = $this->storeRepositoryInterface->delete($store);

        if (!$store) {
            return _error(null, __('messages.delete_error'), HTTP_BAD_REQUEST);
        }

        return _success($store, __('messages.delete_success'), HTTP_SUCCESS);
    }

    public function show($id)
    {
        $store = $this->storeRepositoryInterface->find($id);

        if (!$store) {
            return _error(null, __('messages.store_not_found'), HTTP_BAD_REQUEST);
        }

        return _success($store, __('messages.success'), HTTP_SUCCESS);
    }

    public function list($request) {
        $limit = $request->limit ?? LIMIT;
        $page = $request->page ?? PAGE;

        $stores = $this->storeRepositoryInterface->getListStore($request)->paginate($limit, $page);

        return [
            'stores' => $stores->items(),
            'total' => $stores->total(),
            'current_page' => $stores->currentPage(),
            'last_page' => $stores->lastPage(),
            'per_page' => $stores->perPage(),
        ];
    }
}

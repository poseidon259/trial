<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AddressService;
use Exception;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    /**
     * @var AddressService
     */
    private $addressService;

    public function __construct(
        AddressService $addressService
    )
    {
        $this->addressService = $addressService;
    }

    public function getProvinces()
    {
        try {
            return $this->addressService->getProvinces();
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function getDistricts($provinceId)
    {
        try {
            return $this->addressService->getDistricts($provinceId);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function getWards($districtId)
    {
        try {
            return $this->addressService->getWards($districtId);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }

    public function getAddress($key, $id)
    {
        try {
            return $this->addressService->getAddress($key, $id);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            return _errorSystem();
        }
    }
}

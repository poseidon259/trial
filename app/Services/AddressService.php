<?php

namespace App\Services;

use App\Repositories\District\DistrictRepositoryInterface;
use App\Repositories\Province\ProvinceRepositoryInterface;
use App\Repositories\Ward\WardRepositoryInterface;

class AddressService
{
    /**
     * @var ProvinceRepositoryInterface
     */
    private $provinceRepositoryInterface;

    /**
     * @var DistrictRepositoryInterface
     */
    private $districtRepositoryInterface;

    /**
     * @var WardRepositoryInterface
     */
    private $wardRepositoryInterface;


    public function __construct(
        ProvinceRepositoryInterface $provinceRepositoryInterface,
        DistrictRepositoryInterface $districtRepositoryInterface,
        WardRepositoryInterface $wardRepositoryInterface
    )
    {
        $this->provinceRepositoryInterface = $provinceRepositoryInterface;
        $this->districtRepositoryInterface = $districtRepositoryInterface;
        $this->wardRepositoryInterface = $wardRepositoryInterface;
    }

    public function getProvinces()
    {
        return $this->provinceRepositoryInterface->all();
    }

    public function getDistricts($provinceId)
    {
        return $this->districtRepositoryInterface->getDistrictsByProvince($provinceId);
    }

    public function getWards($districtId)
    {
        return $this->wardRepositoryInterface->getWardsByDistrict($districtId);
    }

    public function getAddress($key, $id)
    {
        switch ($key) {
            case 'province':
                $data = $this->provinceRepositoryInterface->find($id);
                break;
            case 'district':
                $data = $this->districtRepositoryInterface->find($id);
                break;
            case 'ward':
                $data = $this->wardRepositoryInterface->find($id);
                break;
            default:
                $data = null;
        }

        return $data;
    }
}

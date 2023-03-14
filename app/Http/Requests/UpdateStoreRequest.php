<?php

namespace App\Http\Requests;

class UpdateStoreRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'manager_name' => 'required|string',
            'company_name' => 'nullable|string',
            'phone_number' => 'required|string',
            'postal_code' => 'nullable|string',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
            'ward_id' => 'required|integer',
            'house_number' => 'nullable|string',
            'description_list' => 'nullable|string',
            'description_detail' => 'nullable|string',
            'logo' => 'nullable|string',
            'background_image' => 'nullable|string',
            'status' => 'required|integer'
        ];
    }
}

<?php

namespace App\Http\Requests;

class UpdateUserAddressRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'postal_code' => 'nullable|string',
            'phone_number' => 'required|string',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
            'ward_id' => 'required|integer',
            'house_number' => 'nullable|string',
            'is_default' => 'required|integer',
        ];
    }
}

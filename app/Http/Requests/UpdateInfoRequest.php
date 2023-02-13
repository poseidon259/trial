<?php

namespace App\Http\Requests;

class UpdateInfoRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
            'ward_id' => 'required|integer',
            'house_number' => 'nullable|string|max:255',
            'phone_number' => 'required|string',
            'user_name' => 'required|string|min:6|max:14',
            'gender' => 'required|integer',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'birthday' => 'nullable|string',
        ];
    }
}

<?php

namespace App\Http\Requests;

class UpdateUserRequest extends BaseRequest
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
            'role_id' => 'required|integer',
            'postal_code' => 'nullable|string',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
            'ward_id' => 'required|integer',
            'house_number' => 'nullable|string',
            'gender' => 'required|integer',
            'avatar' => 'nullable|string',
            'birthday' => 'nullable|string',
            'status' => 'required|integer',
            'password' => 'nullable|string|min:6',
        ];
    }
}

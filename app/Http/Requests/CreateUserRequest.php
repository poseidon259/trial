<?php

namespace App\Http\Requests;

class CreateUserRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|string',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'role_id' => 'nullable|integer',
            'postal_code' => 'nullable|string',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
            'ward_id' => 'required|integer',
            'house_number' => 'required|string',
            'phone_number' => 'required|string',
            'user_name' => 'nullable|string',
            'gender' => 'nullable|integer',
            'avatar' => 'mimes:jpeg,png,jpg,heic|max:' . MAX_UPLOAD_FILE_SIZE,
            'birthday' => 'nullable|string',
            'status' => 'required|integer',
        ];
    }
}

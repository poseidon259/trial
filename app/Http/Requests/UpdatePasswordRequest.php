<?php

namespace App\Http\Requests;

class UpdatePasswordRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => STRING_REQUIRED,
            'new_password' => 'required|string|confirmed',
            'new_password_confirmation' => 'required|string',
            'code' => STRING_REQUIRED,
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'phone_number' => STRING_NULL,
            'email' => STRING_NULL,
            'password' => STRING_REQUIRED,
            'type' => INT_REQUIRED,
            'system' => INT_REQUIRED
        ];
    }
}

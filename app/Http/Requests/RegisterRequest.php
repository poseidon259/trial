<?php

namespace App\Http\Requests;

class RegisterRequest extends BaseRequest
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
            'first_name' => STRING_REQUIRED . '|max:255',
            'last_name' => STRING_REQUIRED . '|max:255',
            'password' => STRING_REQUIRED . '|min:8|max:16',
        ];
    }
}

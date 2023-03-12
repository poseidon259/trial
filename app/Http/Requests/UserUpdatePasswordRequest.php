<?php

namespace App\Http\Requests;


class UserUpdatePasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'old_password' => 'required|string|min:6|max:255',
            'new_password' => 'required|min:6|max:255|confirmed',
            'new_password_confirmation' => 'required|string|min:6|max:255',
        ];
    }
}

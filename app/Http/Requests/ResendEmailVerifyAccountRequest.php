<?php

namespace App\Http\Requests;

class ResendEmailVerifyAccountRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => STRING_REQUIRED
        ];
    }
}

<?php

namespace App\Http\Requests;

class UpdateStatusOrderRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'status' => 'required|integer'
        ];
    }
}

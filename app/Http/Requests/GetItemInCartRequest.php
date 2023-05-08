<?php

namespace App\Http\Requests;

class GetItemInCartRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'items' => 'required|array',
            'items.*' => 'integer'
        ];
    }
}

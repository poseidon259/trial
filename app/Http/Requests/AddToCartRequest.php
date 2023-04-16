<?php

namespace App\Http\Requests;

class AddToCartRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'product_id' => 'required|integer',
            'child_master_field_id' => 'nullable|integer',
            'quantity' => 'required|integer'
        ];
    }
}

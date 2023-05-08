<?php

namespace App\Http\Requests;

class CreateOrderRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'note' => 'nullable|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|string',
            'province_id' => 'required',
            'district_id' => 'required',
            'ward_id' => 'required',
            'house_number' => 'required|string',
            'gender' => 'nullable|integer',
            'payment_method' => 'required|integer',
            'user_id' => 'nullable|integer',
            'order_items' => 'required|array',
            'order_items.*.product_id' => 'required|integer',
            'order_items.*.child_master_field_id' => 'nullable|integer',
            'order_items.*.quantity' => 'required|integer',
        ];
    }
}

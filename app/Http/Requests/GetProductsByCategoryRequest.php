<?php

namespace App\Http\Requests;


class GetProductsByCategoryRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'page' => 'nullable|integer',
            'limit' => 'nullable|integer',
            'sort_price' => 'nullable|string',
            'newest' =>'nullable|boolean',
            'popular' => 'nullable|boolean',
            'rating' => 'nullable|integer',
            'price_start' => 'nullable|integer',
            'price_end' => 'nullable|integer',
            'date_start' => 'nullable|date',
            'date_end' => 'nullable|date'
        ];
    }
}
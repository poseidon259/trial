<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetListOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'keyword' => 'nullable|string',
            'page' => 'nullable|integer',
            'limit' => 'nullable|integer',
            'start_date' => 'nullable|string',
            'end_date' => 'nullable|string'
        ];
    }
}

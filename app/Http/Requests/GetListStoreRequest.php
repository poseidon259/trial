<?php

namespace App\Http\Requests;

class GetListStoreRequest extends BaseRequest
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
            'status' => 'nullable|integer',
            'page' => 'nullable|integer',
            'limit' => 'nullable|integer',
            'start_date' => 'nullable|string',
            'end_date' => 'nullable|string'
        ];
    }
}

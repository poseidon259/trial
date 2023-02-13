<?php

namespace App\Http\Requests;

class GetListUserRequest extends BaseRequest
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
            'role_id' => 'nullable|integer',
            'store_id' => 'nullable|integer',
            'page' => 'nullable|integer',
            'limit' => 'nullable|integer',
            'start_date' => 'nullable|string',
            'end_date' => 'nullable|string'
        ];
    }
}

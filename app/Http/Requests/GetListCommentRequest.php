<?php

namespace App\Http\Requests;

class GetListCommentRequest extends BaseRequest
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
            'start_date' => 'nullable|string',
            'end_date' => 'nullable|string'
        ];
    }
}

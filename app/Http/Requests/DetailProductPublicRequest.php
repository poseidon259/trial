<?php

namespace App\Http\Requests;

class DetailProductPublicRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'child_master_field_id' => 'nullable|integer'
        ];
    }
}

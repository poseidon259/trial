<?php

namespace App\Http\Requests;

class CreateCategoryChildRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'names' => 'array',
            'names.*' => 'required|string',
        ];
    }
}

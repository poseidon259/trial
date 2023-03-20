<?php

namespace App\Http\Requests;

class UpdateProductRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'created_by' => 'required|integer',
            'category_id' => 'required|integer',
            'description_list' => 'nullable|string',
            'description_detail' => 'nullable|string',
            'status' => 'required|integer',
            'sale_price' => 'nullable|integer|',
            'origin_price' => 'required|integer|gt:sale_price',
            'product_code' => 'required|string|max:100',
            'stock' => 'required|integer',
            'images' => 'array',
            'images.*.image' => 'required|mimes:jpeg,png,jpg,heic|max:' . MAX_UPLOAD_FILE_SIZE,
            'images.*.type' => 'required|integer',
            'images.*.sort' => 'required|integer',
            'images.*.status' => 'required|integer',
        ];
    }
}

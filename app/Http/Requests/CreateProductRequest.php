<?php

namespace App\Http\Requests;

class CreateProductRequest extends BaseRequest
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
            'category_id' => 'required|integer',
            'category_child_id' => 'nullable|integer',
            'description_list' => 'nullable|string',
            'description_detail' => 'nullable|string',
            'status' => 'required|integer',
            'sale_price' => 'nullable|integer',
            'origin_price' => 'required|integer|',
            'product_code' => 'nullable|string|max:100',
            'stock' => 'required|integer',
            'images' => 'array',
            'images.*' => 'required|mimes:jpeg,png,jpg,heic|max:' . MAX_UPLOAD_FILE_SIZE,
            // 'images.*.type' => 'nullable|integer',
            // 'images.*.sort' => 'nullable|integer',
            // 'images.*.status' => 'nullable|integer',
            'master_fields' => 'array',
            'master_fields.*.name' => 'nullable|string|max:255',
            'master_fields.*.childs' => 'nullable|array',
            'master_fields.*.childs.*.name' => 'required|string|max:255',
            'master_fields.*.childs.*.sale_price' => 'nullable|integer',
            'master_fields.*.childs.*.origin_price' => 'required|integer',
            'master_fields.*.childs.*.stock' => 'required|integer',
            'master_fields.*.childs.*.product_code' => 'nullable|string|max:100',
        ];
    }
}

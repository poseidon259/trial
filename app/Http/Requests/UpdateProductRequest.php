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
            'created_by' => 'nullable|integer',
            'category_id' => 'required|integer',
            'category_child_id' => 'nullable|integer',
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
            'master_fields' => 'array',
            'master_fields.*.id' => 'required|integer',
            'master_fields.*.is_delete' => 'required|integer',
            'master_fields.*.name' => 'required|string|max:255',
            'master_fields.*.childs' => 'required|array',
            'master_fields.*.childs.*.id' => 'required|integer',
            'master_fields.*.childs.*.is_delete' => 'required|integer',
            'master_fields.*.childs.*.name' => 'required|string|max:255',
            'master_fields.*.childs.*.sale_price' => 'required|integer',
            'master_fields.*.childs.*.origin_price' => 'required|integer|gt:master_fields.*.childs.*.sale_price',
            'master_fields.*.childs.*.stock' => 'required|integer',
            'master_fields.*.childs.*.product_code' => 'nullable|string|max:100',
        ];
    }
}

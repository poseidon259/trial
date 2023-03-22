<?php

namespace App\Http\Requests;


class CreateBannerRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'images' => 'array',
            'images.*.image' => 'required|mimes:jpeg,png,jpg,heic|max:' . MAX_UPLOAD_FILE_SIZE,
            'images.*.link_url' => 'nullable|string',
            'images.*.sort' => 'nullable|integer',
            'images.*.display' => 'nullable|integer',
        ];
    }
}

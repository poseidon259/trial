<?php

namespace App\Http\Requests;

class CreateCommentPublicRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'content' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'nullable|integer',
            'images' => 'array',
            'images.*.image' => 'mimes:jpeg,png,jpg,heic|max:' . MAX_UPLOAD_FILE_SIZE,
        ];
    }
}

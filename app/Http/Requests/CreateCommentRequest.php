<?php

namespace App\Http\Requests;

class CreateCommentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'nullable|integer',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'fake_avatar' => 'nullable|string',
            'images' => 'array',
            'images.*.image' => 'mimes:jpeg,png,jpg,heic|max:' . MAX_UPLOAD_FILE_SIZE,
        ];
    }
}

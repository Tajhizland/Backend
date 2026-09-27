<?php

namespace App\Http\Requests\Admin\InstagramPost;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInstagramPostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'caption' => ['nullable', 'string'],
            'url' => [
                'required', 'string', 'max:256',
                // آدرس فعلی خودِ پست نباید تکراری حساب شود
                Rule::unique('instagram_posts', 'url')->ignore($this->route('id')),
            ],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

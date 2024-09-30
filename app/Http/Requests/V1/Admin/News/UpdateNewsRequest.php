<?php

namespace App\Http\Requests\V1\Admin\News;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNewsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "id" => ["required","integer","exists:App\Models\News"],
            "title" => ["required","string"],
            "url" => ["required","string" , Rule::unique('news')->ignore($this->id)],
            "content" => ["required","string"],
            "img" => ['nullable' , 'image','mimes:jpeg,png,jpg,gif,svg,webp'],
            "published" => ["required","integer","in:1,0"],
            "static" => ["nullable"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

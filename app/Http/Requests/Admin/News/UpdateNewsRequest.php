<?php

namespace App\Http\Requests\Admin\News;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNewsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "title" => ["required","string"],
            "url" => ["required","string" , Rule::unique('news')->ignore($this->route('id'))],
            "content" => ["required","string"],
            "image" => ['nullable' , 'image','mimes:jpeg,png,jpg,gif,svg,webp'],
            "categoryId" => ["nullable","exists:App\Models\BlogCategory,id"],
            "published" => ["required","integer","in:1,0"],
            "static" => ["nullable"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

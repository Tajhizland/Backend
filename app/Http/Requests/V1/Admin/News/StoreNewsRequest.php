<?php

namespace App\Http\Requests\V1\Admin\News;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "title" => ["required","string"],
            "url" => ["required","string" ,"unique:App\Models\News"],
            "content" => ["required","string"],
            "img" =>  ['nullable' , 'image','mimes:jpeg,png,jpg,gif,svg,webp'],
            "published" => ["required","integer","in:1,0"],
            "static" => ["nullable"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace App\Http\Requests\V1\Shop\Vlog;

use Illuminate\Foundation\Http\FormRequest;

class GetBlogByCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "url"=>["required"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

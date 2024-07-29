<?php

namespace App\Http\Requests\V1\Shop;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "query"=>"required|string"
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

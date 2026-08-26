<?php

namespace App\Http\Requests\Shop\Search;

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

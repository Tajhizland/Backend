<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class GroupChangeSnappayPercentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'percent' => ['required', 'integer', 'min:0', 'max:100'],
        ];
    }
}

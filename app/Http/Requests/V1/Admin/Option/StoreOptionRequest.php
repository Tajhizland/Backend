<?php

namespace App\Http\Requests\V1\Admin\Option;

use Illuminate\Foundation\Http\FormRequest;

class StoreOptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer','exists:App\Models\Category'],
            'title' => ['required','string'],
            'status' => ['required', 'integer' ,'in:0,1'],
            'items.*.title' => ['required', 'string' ],
            'items.*.status' => ['required','integer' ,'in:0,1'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

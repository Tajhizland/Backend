<?php

namespace App\Http\Requests\V1\Admin\Option;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer','exists:App\Models\Option'],
            'category_id' => ['required', 'integer','exists:App\Models\Category'],
            'title' => ['required','string'],
            'status' => ['required', 'integer' ,'in:0,1'],
            'items.*.id' => ['integer', 'exists:App\Models\OptionItem' ],
            'items.*.title' => ['required', 'string' ],
            'items.*.status' => ['required','integer' ,'in:0,1'],
         ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

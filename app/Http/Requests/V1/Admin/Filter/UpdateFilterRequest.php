<?php

namespace App\Http\Requests\V1\Admin\Filter;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required','exists:App\Models\Filter'],
            'name' => ['required'],
            'category_id' => ['required', 'integer','exists:App\Models\Category'],
            'type' => [  'integer' , 'in:2,1'],
            'items.*.id' => [ 'integer' ,'exists:App\Models\FilterItem' ],
            'items.*.value' => ['required', 'string'  ],
            'items.*.status' => ['required', 'integer' , 'in:0,1'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

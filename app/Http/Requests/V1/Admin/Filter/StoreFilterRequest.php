<?php

namespace App\Http\Requests\V1\Admin\Filter;

use Illuminate\Foundation\Http\FormRequest;

class StoreFilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'category_id' => ['required', 'integer'],
            'status' => ['required', 'integer' , 'in:0,1'],
            'type' => [  'integer' , 'in:2,1'],
            'items.*.value' => ['required', 'string'  ],
            'items.*.status' => ['required', 'integer' , 'in:0,1'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

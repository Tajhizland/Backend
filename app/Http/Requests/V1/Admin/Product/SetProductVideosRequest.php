<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class SetProductVideosRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id'=>['required','integer'],
            'item.*.id' => ['nullable','integer','exists:App\Models\ProductColor'],
            'item.*.title' => ['required','string'],
            'item.*.vlogId' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

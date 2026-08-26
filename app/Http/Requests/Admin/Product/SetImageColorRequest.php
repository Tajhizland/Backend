<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SetImageColorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'image' => ['required', 'array'],
            'image.*.id' => [
                'required',
                'integer',
                Rule::exists('product_images', 'id')->where('product_id', $this->input('product_id')),
            ],
            'image.*.product_color_id' => [
                'nullable',
                'integer',
                Rule::exists('product_colors', 'id')->where('product_id', $this->input('product_id')),
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

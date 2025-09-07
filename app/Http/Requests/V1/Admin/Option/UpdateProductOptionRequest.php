<?php

namespace App\Http\Requests\V1\Admin\Option;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductOptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'options' => ['required', 'array'],
            'options.*.id' => ['nullable', 'integer'],
            'options.*.productId' => ['required', 'exists:App\Models\Product,id'],
            'options.*.value' => ['nullable', 'string'],
            'options.*.option_item_id' => ['required', 'exists:App\Models\OptionItem,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

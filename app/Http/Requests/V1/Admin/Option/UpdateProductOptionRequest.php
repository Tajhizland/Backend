<?php

namespace App\Http\Requests\V1\Admin\Option;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductOptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['nullable'],
            'productId' => ['required', 'exists:App\Models\Product,id'],
            'value' => ['nullable', 'string'],
            'option_item_id' => ['required', 'exists:App\Models\OptionItem,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

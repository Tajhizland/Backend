<?php

namespace App\Http\Requests\V1\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;

class DeleteOrderItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:order_items,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

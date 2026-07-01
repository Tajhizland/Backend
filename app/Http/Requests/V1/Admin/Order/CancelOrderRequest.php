<?php

namespace App\Http\Requests\V1\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;

class CancelOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:orders,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace App\Http\Requests\Admin\Order;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', 'integer', Rule::enum(OrderStatus::class)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

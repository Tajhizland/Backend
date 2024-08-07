<?php

namespace App\Http\Requests\V1\Admin\OnHoldOrder;

use Illuminate\Foundation\Http\FormRequest;

class OnHoldOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer' , 'exists:App\Models\OnHoldOrder'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

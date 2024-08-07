<?php

namespace App\Http\Requests\V1\Admin\Delivery;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeliveryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required'],
            'name' => ['required'],
            'description' => ['nullable'],
            'status' => ['required', 'integer'],
            'price' => ['required', 'integer'],
            'logo' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace App\Http\Requests\V1\Admin\Gateway;

use Illuminate\Foundation\Http\FormRequest;

class StoreGatewayRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'status' => ['required', 'integer'],
            'description' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

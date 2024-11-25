<?php

namespace App\Http\Requests\V1\Admin\Landing;

use Illuminate\Foundation\Http\FormRequest;

class SetLandingProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'landing_id' => ['required', 'exists:landings,id'],
            'product_id' => ['required', 'exists:products,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

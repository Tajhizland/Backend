<?php

namespace App\Http\Requests\V1\Admin\Landing;

use Illuminate\Foundation\Http\FormRequest;

class SetCategoryLandingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'landing_id' => ['required', 'exists:landings'],
            'category_id' => ['required', 'exists:categories'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

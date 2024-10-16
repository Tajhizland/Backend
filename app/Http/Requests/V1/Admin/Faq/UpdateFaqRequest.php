<?php

namespace App\Http\Requests\V1\Admin\Faq;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFaqRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer','exists:App\Models\Faq'],
            'question' => ['required'],
            'answer' => ['required'],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

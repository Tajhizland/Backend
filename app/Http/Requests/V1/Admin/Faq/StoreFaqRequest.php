<?php

namespace App\Http\Requests\V1\Admin\Faq;

use Illuminate\Foundation\Http\FormRequest;

class StoreFaqRequest extends FormRequest
{
    public function rules(): array
    {
        return [
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

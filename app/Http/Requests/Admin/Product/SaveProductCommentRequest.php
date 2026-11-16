<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class SaveProductCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'author_name' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string', 'max:10000'],
            'rating' => ['required', 'integer', 'between:1,5'],
        ];
    }
}

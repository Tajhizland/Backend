<?php

namespace App\Http\Requests\V1\Shop\Comment;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'productId' => ['required', 'integer'],
            'rating' => ['required', 'integer', "min:1", "max:5"],
            'text' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

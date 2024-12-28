<?php

namespace App\Http\Requests\V1\Admin\Poster;

use Illuminate\Foundation\Http\FormRequest;

class StorePosterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => ['required','image','mimes:jpeg,png,jpg,gif,svg,webp'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

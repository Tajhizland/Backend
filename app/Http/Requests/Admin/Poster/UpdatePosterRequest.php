<?php

namespace App\Http\Requests\Admin\Poster;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePosterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => ['required','image:allow_svg','mimes:jpeg,png,jpg,gif,svg,webp'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

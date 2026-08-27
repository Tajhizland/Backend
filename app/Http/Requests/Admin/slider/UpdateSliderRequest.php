<?php

namespace App\Http\Requests\Admin\slider;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSliderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'url' => ['required'],
            'type' => ['required','string','in:desktop,mobile'],
            'image' => ['nullable' , 'image:allow_svg','mimes:jpeg,png,jpg,gif,svg,webp'],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

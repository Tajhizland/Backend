<?php

namespace App\Http\Requests\V1\Admin\slider;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSliderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required'],
            'title' => ['required'],
            'url' => ['required'],
            'type' => ['required','string','in:desktop,mobile'],
            'image' => ['nullable' , 'image','mimes:jpeg,png,jpg,gif,svg,webp'],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

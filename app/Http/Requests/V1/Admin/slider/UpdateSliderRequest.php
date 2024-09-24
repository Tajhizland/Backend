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
            'image' => ['required'],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace App\Http\Requests\V1\Admin\Landing;

use Illuminate\Foundation\Http\FormRequest;

class SetBannerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => ['required','image'],
            'url' => ['required'],
            'landing_id' => ['required', 'exists:landings,id'],
            'slider' => ['required', 'integer','in:0,1'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

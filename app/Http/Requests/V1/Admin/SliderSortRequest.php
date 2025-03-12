<?php

namespace App\Http\Requests\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SliderSortRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "slider.*.id" => "required|numeric|exists:App\Models\Slider,id",
            "slider.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

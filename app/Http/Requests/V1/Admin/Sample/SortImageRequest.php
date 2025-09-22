<?php

namespace App\Http\Requests\V1\Admin\Sample;

use Illuminate\Foundation\Http\FormRequest;

class SortImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "image.*.id" => "required|numeric|exists:App\Models\SampleImage,id",
            "image.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace App\Http\Requests\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ImageSortRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "image.*.id" => "required|numeric|exists:App\Models\ProductImage,id",
            "image.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

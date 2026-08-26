<?php

namespace App\Http\Requests\Admin\VlogCategory;

use Illuminate\Foundation\Http\FormRequest;

class VlogCategorySortRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "vlogs.*.id" => "required|numeric|exists:App\Models\VlogCategory,id",
            "vlogs.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

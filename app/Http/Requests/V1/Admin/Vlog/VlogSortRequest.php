<?php

namespace App\Http\Requests\V1\Admin\Vlog;

use Illuminate\Foundation\Http\FormRequest;

class VlogSortRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "vlog.*.id" => "required|numeric|exists:App\Models\Vlog,id",
            "vlog.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

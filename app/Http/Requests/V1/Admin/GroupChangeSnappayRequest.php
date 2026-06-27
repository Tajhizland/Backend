<?php

namespace App\Http\Requests\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GroupChangeSnappayRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "ids" => "array",
            "snappay" => ["required"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

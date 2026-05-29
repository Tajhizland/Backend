<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class GroupChangePercentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "ids" => "array",
            "percent"=>["required"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

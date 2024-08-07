<?php

namespace App\Http\Requests\V1\Admin\Returned;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReturnedStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "id"=>['required','integer']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace App\Http\Requests\V1\Shop\Address;

use Illuminate\Foundation\Http\FormRequest;

class ChangeActiveAddressRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "id"=>"required"
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

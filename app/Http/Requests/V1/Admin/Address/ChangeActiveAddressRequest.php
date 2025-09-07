<?php

namespace App\Http\Requests\V1\Admin\Address;

use Illuminate\Foundation\Http\FormRequest;

class ChangeActiveAddressRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['exists:App\Models\Address,id'],
            'user_id' => ['required', 'integer' , 'exists:App\Models\User,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace App\Http\Requests\V1\Admin\PhoneBock;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhoneBockRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required' , 'exists:phone_bocks,id'],
            'mobile' => ['required'],
            'name' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

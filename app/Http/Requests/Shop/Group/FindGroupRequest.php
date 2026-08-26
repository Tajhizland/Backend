<?php

namespace App\Http\Requests\Shop\Group;

use Illuminate\Foundation\Http\FormRequest;

class FindGroupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

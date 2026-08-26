<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'permissions.*' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace App\Http\Requests\V1\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:App\Models\Role'],
            'name' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

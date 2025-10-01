<?php

namespace App\Http\Requests\V1\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'permission.*' => ['required',"array"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

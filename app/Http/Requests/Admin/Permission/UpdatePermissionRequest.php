<?php

namespace App\Http\Requests\Admin\Permission;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:App\Models\Permission'],
            'name' => ['required'],
            'value' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace App\Http\Requests\V1\Admin\Permission;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'value' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

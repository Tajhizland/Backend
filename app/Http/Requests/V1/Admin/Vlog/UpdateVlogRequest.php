<?php

namespace App\Http\Requests\V1\Admin\Vlog;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVlogRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required'],
            'title' => ['required'],
            'description' => ['nullable'],
            'url' => ['required'],
            'video' => ['nullable'],
            'poster' => ['nullable'],
            'categoryId' => ['required',"exists:App\Models\VlogCategory,id"],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

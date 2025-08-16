<?php

namespace App\Http\Requests\V1\Admin\Option;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOptionItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['nullable'],
            'categoryId' => ['required','exists:App\Models\Category,id'],
            'title' => ['required','string'],
            'status' => ['required', 'integer' ,'in:0,1'],
         ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

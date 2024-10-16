<?php

namespace App\Http\Requests\V1\Admin\Page;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'url' => ['required'],
            'image' => ['nullable' , 'image' ,'mimes:jpeg,png,jpg,gif,svg,webp'],
            'content' => ['required'],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

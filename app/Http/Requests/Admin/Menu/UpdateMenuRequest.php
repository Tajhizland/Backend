<?php

namespace App\Http\Requests\Admin\Menu;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'parent_id' => ['nullable','integer'],
            'url' => ['required'],
            'status' => ['required','in:0,1'],
            'category_id' => ['nullable'],
            'banner_link' => ['nullable'],
            'banner_logo' =>  ['nullable' , 'image:allow_svg','mimes:jpeg,png,jpg,gif,svg,webp'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

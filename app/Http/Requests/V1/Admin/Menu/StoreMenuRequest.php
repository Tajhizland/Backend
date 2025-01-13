<?php

namespace App\Http\Requests\V1\Admin\Menu;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
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
            'banner_logo' =>  ['nullable' , 'image','mimes:jpeg,png,jpg,gif,svg,webp'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

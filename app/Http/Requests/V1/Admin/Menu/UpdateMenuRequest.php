<?php

namespace App\Http\Requests\V1\Admin\Menu;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required' , 'exists:App\Models\Menu'],
            'title' => ['required'],
            'parent_id' => ['nullable', 'integer',"exists:App\Models\Menu,id"],
            'url' => ['nullable'],
            'banner_title' => ['nullable'],
            'banner_link' => ['nullable'],
            'banner_logo' =>  ['nullable' , 'image','mimes:jpeg,png,jpg,gif,svg,webp'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

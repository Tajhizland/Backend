<?php

namespace App\Http\Requests\V1\Admin\HomepageCategory;

use Illuminate\Foundation\Http\FormRequest;

class SetIconRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "id"=>"required",
            "icon"=>["required","image" , 'mimes:jpeg,png,jpg,gif,svg,webp'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

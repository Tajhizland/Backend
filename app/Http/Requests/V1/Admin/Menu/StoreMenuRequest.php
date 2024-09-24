<?php

namespace App\Http\Requests\V1\Admin\Menu;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'parent_id' => ['nullable', 'integer',"exists:App\Models\Menu,id"],
            'url' => ['nullable'],
            'banner_title' => ['nullable'],
            'banner_link' => ['nullable'],
            'banner_logo' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

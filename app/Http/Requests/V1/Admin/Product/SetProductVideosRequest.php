<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class SetProductVideosRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer'],
            'title' => ['required', 'string'],
            'vlogId' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

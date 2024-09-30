<?php

namespace App\Http\Requests\V1\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file'=> ['required', 'file'],
            'product_id'=> ['required', 'exists:products,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

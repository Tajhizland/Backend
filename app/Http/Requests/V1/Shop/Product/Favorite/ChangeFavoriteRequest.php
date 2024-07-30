<?php

namespace App\Http\Requests\V1\Shop\Product\Favorite;

use Illuminate\Foundation\Http\FormRequest;

class ChangeFavoriteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "productId"=>"required|int"
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

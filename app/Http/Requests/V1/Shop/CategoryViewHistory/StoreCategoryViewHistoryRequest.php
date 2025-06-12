<?php

namespace App\Http\Requests\V1\Shop\CategoryViewHistory;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryViewHistoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "category_id"=>["required","exists:categories,id"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

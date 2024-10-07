<?php

namespace App\Http\Requests\V1\Admin\Filter;

use Illuminate\Foundation\Http\FormRequest;

class SetFilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "category_id" => "required|exists:categories,id",
            "filter.*.id"=>"numeric|exists:filters,id|nullable",
            "filter.*.name"=>"string|required",
            "filter.*.item.*.id"=>"numeric|exists:filter_items,id|nullable",
            "filter.*.item.*.value"=>"string|required",
            "filter.*.item.*.status"=>"numeric|in:0,1|required",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

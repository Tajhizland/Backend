<?php

namespace App\Http\Requests\V1\Shop\Returned;

use Illuminate\Foundation\Http\FormRequest;

class StoreReturnedRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'integer'],
            'order_item_id' => ['required', 'integer' ],
            'count' => ['required', 'integer',"min:1"],
            'description' => ['required','string'],
            'file' => ['nullable' , 'file'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

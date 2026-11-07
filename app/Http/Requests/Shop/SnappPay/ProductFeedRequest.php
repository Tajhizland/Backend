<?php

namespace App\Http\Requests\Shop\SnappPay;

use Illuminate\Foundation\Http\FormRequest;

class ProductFeedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'size' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'id' => ['sometimes', 'integer', 'min:1', 'prohibits:slug'],
            'slug' => ['sometimes', 'string', 'min:1', 'max:256', 'prohibits:id'],
        ];
    }
}

<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;

class DigipayCalcRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "start_date" => ["required", "date_format:Y-m-d"],
            "end_date" => ["required", "date_format:Y-m-d", "after_or_equal:start_date"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

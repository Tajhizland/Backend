<?php

namespace App\Http\Requests\V1\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;

class DigipayCalcRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "start_date" => ["required"],
            "end_date" => ["required"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace App\Http\Requests\V1\Shop\Payment;

use Illuminate\Foundation\Http\FormRequest;

class SnappPayEligibleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "amount" => ["required", "integer", "min:1"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace App\Http\Requests\Shop\Wallet;

use Illuminate\Foundation\Http\FormRequest;

class ChargeWalletRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "amount" => ["required", "numeric"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

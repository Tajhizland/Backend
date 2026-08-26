<?php

namespace App\Http\Requests\Shop\Delivery;

use Illuminate\Foundation\Http\FormRequest;

class SelectDeliveryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "id"=>["required","exists:App\Models\Delivery,id"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

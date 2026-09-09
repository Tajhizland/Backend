<?php

namespace App\Http\Requests\Shop\Marketing;

use App\Enums\MarketingEventType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TrackEventRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "type" => ["required", "string", Rule::in(MarketingEventType::clientTrackable())],
            "product_id" => ["nullable", "integer", "exists:products,id"],
            "category_id" => ["nullable", "integer", "exists:categories,id"],
            "quantity" => ["nullable", "integer", "min:1", "max:1000"],
            "meta" => ["nullable", "array"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace App\Http\Requests\Admin\Sample;

use Illuminate\Foundation\Http\FormRequest;

class SortVideoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "video.*.id" => "required|numeric|exists:App\Models\SampleVideo,id",
            "video.*.sort" => "required|numeric",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

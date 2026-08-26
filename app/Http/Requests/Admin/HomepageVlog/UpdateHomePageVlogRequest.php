<?php

namespace App\Http\Requests\Admin\HomepageVlog;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomePageVlogRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "vlogId"=>["required"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

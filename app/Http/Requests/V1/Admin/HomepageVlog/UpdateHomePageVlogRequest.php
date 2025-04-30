<?php

namespace App\Http\Requests\V1\Admin\HomepageVlog;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomePageVlogRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "id"=>["required"],
            "vlogId"=>["required"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

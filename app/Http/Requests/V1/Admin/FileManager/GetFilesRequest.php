<?php

namespace App\Http\Requests\V1\Admin\FileManager;

use Illuminate\Foundation\Http\FormRequest;

class GetFilesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "model_id"=>"required",
            "model_type"=>"required"
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

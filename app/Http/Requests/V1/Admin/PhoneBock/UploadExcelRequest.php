<?php

namespace App\Http\Requests\V1\Admin\PhoneBock;

use Illuminate\Foundation\Http\FormRequest;

class UploadExcelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

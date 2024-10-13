<?php

namespace App\Http\Requests\V1\Admin\News;

use Illuminate\Foundation\Http\FormRequest;

class NewsFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file'=> ['required', 'file'],
            'news_id'=> ['required', 'exists:App\Models\News,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

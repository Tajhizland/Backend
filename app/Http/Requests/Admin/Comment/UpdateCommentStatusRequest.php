<?php

namespace App\Http\Requests\Admin\Comment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "id"=>["required","integer"]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

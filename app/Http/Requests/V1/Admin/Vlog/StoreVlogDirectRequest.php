<?php

namespace App\Http\Requests\V1\Admin\Vlog;

use Illuminate\Foundation\Http\FormRequest;

/**
 * نسخه‌ی آپلود مستقیم: به‌جای فایل ویدیو، کلید آبجکتی که کلاینت خودش
 * روی S3 آپلود کرده دریافت می‌شود. صحت و مالکیت کلید در
 * DirectUploadService بررسی می‌شود.
 */
class StoreVlogDirectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'description' => ['nullable'],
            'url' => ['required'],
            'videoKey' => ['required', 'string', 'max:512'],
            'poster' => ['required', 'image'],
            'categoryId' => ['required', "exists:App\Models\VlogCategory,id"],
            'status' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

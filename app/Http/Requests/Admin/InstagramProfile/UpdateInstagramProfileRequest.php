<?php

namespace App\Http\Requests\Admin\InstagramProfile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstagramProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'url' => [
                'required', 'string', 'max:2048', 'url:http,https',
                'regex:~^https?://(?:www\.)?instagram\.com/[a-z0-9._]{1,30}/?(?:\?[^\s#]*)?(?:#[^\s]*)?$~i',
            ],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'prohibited_if:remove_avatar,1'],
            'remove_avatar' => ['sometimes', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'نام نمایشی',
            'url' => 'آدرس اینستاگرام',
            'bio' => 'بایو',
            'avatar' => 'تصویر پروفایل',
            'remove_avatar' => 'حذف تصویر پروفایل',
        ];
    }

    public function messages(): array
    {
        return ['url.regex' => 'آدرس کامل پروفایل اینستاگرام را وارد کنید؛ برای مثال https://www.instagram.com/username/'];
    }

    public function authorize(): bool
    {
        return true;
    }
}

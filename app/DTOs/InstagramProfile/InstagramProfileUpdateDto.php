<?php

namespace App\DTOs\InstagramProfile;

use Illuminate\Http\UploadedFile;

readonly class InstagramProfileUpdateDto
{
    public function __construct(
        public string $name,
        public string $url,
        public ?string $bio = null,
        public ?UploadedFile $avatar = null,
        public bool $remove_avatar = false,
    ) {}
}

<?php

namespace App\Http\Resources\InstagramProfile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstagramProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'url' => $this->url,
            'username' => trim((string) parse_url($this->url, PHP_URL_PATH), '/'),
            'bio' => $this->bio,
            'avatar' => $this->avatar,
        ];
    }
}

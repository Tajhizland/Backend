<?php

namespace App\Http\Resources\V1\User;

use App\Http\Resources\V1\Role\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'national_code' => $this->national_code,
            'username' => $this->username,
            'avatar' => $this->avatar,
            'role' => $this->role,
            'role_id' => $this->role_id,
            'wallet' => $this->wallet,
            'roles' => new RoleResource($this->whenLoaded('roles')),
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}

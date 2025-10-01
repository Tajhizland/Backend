<?php

namespace App\Http\Resources;

use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin RolePermission */
class RolePermissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'role_id' => $this->role_id,
            'permission_id' => $this->permission_id,

            'role' => new RoleResource($this->whenLoaded('role')),
            'permission' => new PermissionResource($this->whenLoaded('permission')),
        ];
    }
}

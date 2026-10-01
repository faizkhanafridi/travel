<?php

namespace App\Http\Resources\Authentication;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'avatar'     => $this->avatar,
            'user_type'  => $this->user_type,
            'status'     => $this->status,
            'roles'      => RoleResource::collection($this->whenLoaded('roles')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
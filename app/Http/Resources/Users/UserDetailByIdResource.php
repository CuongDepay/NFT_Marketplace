<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\UserRelationship\FollowerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailByIdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "custom_url" => $this->custom_url,
            "phone_number" => $this->phone_number,
            "address" => $this->address,
            "introduce" => $this->introduce,
            "email_verified_at" => $this->email_verified_at,
            'followers' => FollowerResource::collection($this->followers),
            'followings_count' => FollowerResource::collection($this->followings)->count(),
        ];
    }
}

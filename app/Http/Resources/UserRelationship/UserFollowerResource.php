<?php

namespace App\Http\Resources\UserRelationship;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFollowerResource extends JsonResource
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
            "avatar" => $this->avatar,
            "background" => $this->background,
            'followers' => $this->when($this->followers->isNotEmpty(), FollowerResource::collection($this->followers)),
        ];
    }
}

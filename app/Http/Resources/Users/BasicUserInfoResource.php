<?php

namespace App\Http\Resources\Users;

use App\Enums\User\Gender;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BasicUserInfoResource extends JsonResource
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
            "gender" => Gender::getKey((int)$this->gender),
            "email" => $this->email,
            "custom_url" => $this->custom_url,
            "phone_number" => $this->phone_number,
            "address" => $this->address,
            "introduce" => $this->introduce,
            "email_verified_at" => $this->email_verified_at,
            "avatar" => $this->avatar,
            "background" => $this->background,
            "country" => $this->country,
            "state" => $this->state,
            "city" => $this->city,
            "zip_code" => $this->zip_code
        ];
    }
}

<?php

namespace App\Http\Resources\Collection;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyCollectionResource extends JsonResource
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
            "url" => $this->url,
            "name" => $this->name,
            "description" => $this->description,
            "price" => $this->price,
            "logo_image_url" => $this->logo_image_url,
            "featured_image_url" => $this->featured_image_url,
            "cover_image_url" => $this->cover_image_url,
        ];
    }
}

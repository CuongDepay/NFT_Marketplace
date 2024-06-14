<?php

namespace App\Http\Resources\NFT;

use App\Http\Resources\PriceHistory\PriceHistoryResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class NFTInfoResource extends JsonResource
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
            "created_at" => $this->created_at,
            "title" => $this->title,
            "description" => $this->description,
            "view" => $this->view,
            "image_url" => $this->image_url,
            "quantity" => $this->quantity,
            "starting_date" => $this->starting_date,
            "expiration_date" => $this->expiration_date,
            "prices" => $this->price,
            "collection_id" => $this->collection_id
        ];
    }
}

<?php

namespace App\Http\Resources\Cart;

use App\Http\Resources\Collection\CollectionResource;
use App\Http\Resources\NFT\NFTInfoResource;
use App\Http\Resources\Users\BasicUserInfoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartDetailResource extends JsonResource
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
            "updated_at" => $this->updated_at,
            "nft" => new NFTInfoResource($this->whenLoaded("nft")),
            "collection" => new CollectionResource($this->nft->collection),
            "author" => new BasicUserInfoResource($this->nft->collection->user),
            "quantity" => $this->quantity,
        ];
    }
}

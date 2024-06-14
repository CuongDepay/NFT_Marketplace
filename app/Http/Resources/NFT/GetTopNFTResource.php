<?php

namespace App\Http\Resources\NFT;

use App\Http\Resources\Collection\CollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetTopNFTResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_quantity' => $this->total_quantity,
            'nft' =>  new NFTInfoResource($this->nft),
            'collection' => new CollectionResource($this->nft->collection)
        ];
    }
}

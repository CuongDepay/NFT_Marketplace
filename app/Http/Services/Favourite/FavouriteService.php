<?php

namespace App\Http\Services\Favourite;

use App\Http\Resources\NFT\NFTInfoResource;
use App\Models\Favourite;

class FavouriteService
{
    public function addNFTtoFavourite($data)
    {
        try {
            $item = Favourite::create([
                "user_id" => $data["user_id"],
                "nft_id" => $data["nft_id"]
            ]);
            return $item;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function isExistFavourite($data)
    {
        $itemExist = Favourite::where("user_id", $data["user_id"])->where("nft_id", $data["nft_id"])->first();
        if ($itemExist) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteFavourite($data)
    {
        Favourite::where("user_id", $data["user_id"])->where("nft_id", $data["nft_id"])->delete();
    }

    public function getListFavouriteByIdUser($userId)
    {
        $favourites = Favourite::with('nft')->where('user_id', $userId)->paginate(10);
        if ($favourites->isEmpty()) {
            return collect();
        }
        $favourites->getCollection()->transform(function ($favourite) {
            return new NFTInfoResource($favourite->nft);
        });
        return $favourites;
    }
}

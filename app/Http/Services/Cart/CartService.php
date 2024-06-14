<?php

namespace App\Http\Services\Cart;

use App\Models\CartDetail;
use PHPUnit\Event\Code\Throwable;

class CartService
{
    /**
     * Add NFT to cart or update quantity if it already exists.
     *
     * @param string $userId
     * @param string $nftId
     * @param int $quantity
     * @return void
     * @throws Exception
     */
    public function addNFTToCart($userId, $nftId, $quantity)
    {
        try {
            $cartDetail = CartDetail::where('user_id', $userId)->where('nft_id', $nftId)->first();
            if ($cartDetail) {
                $cartDetail->quantity += $quantity;
                $cartDetail->save();
            } else {
                $dataCart = [
                    "user_id" => $userId,
                    'nft_id' => $nftId,
                    'quantity' => $quantity
                ];
                CartDetail::create($dataCart);
            }
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function updateNFTInCart($userId, $nftId, $quantity)
    {
        $cartDetail = CartDetail::where('user_id', $userId)->where('nft_id', $nftId)->first();
        if ($cartDetail) {
            $newQuantity = $quantity;
            if ($newQuantity <= 0) {
                $cartDetail->delete();
            } else {
                $cartDetail->quantity = $newQuantity;
                $cartDetail->save();
            }

            return true;
        }
        return false;
    }

    public function removeNFTInCart($userId, $nftId)
    {
        $cartDetail = CartDetail::where('user_id', $userId)->where('nft_id', $nftId)->first();

        if ($cartDetail) {
            $cartDetail->delete();
            return true;
        }

        return false;
    }

    public function getListNFTInCart($userId)
    {
        $nftInCart = CartDetail::with('nft')->where('user_id', $userId)->get();
        return $nftInCart;
    }
}

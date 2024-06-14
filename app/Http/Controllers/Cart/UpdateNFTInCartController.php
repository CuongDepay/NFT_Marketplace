<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\UpdateNFTInCartRequest;
use App\Http\Services\Cart\CartService;

class UpdateNFTInCartController extends Controller
{
    /**
     * Class constructor.
     */
    private CartService $cartService;

        /**
     * @OA\Put(
     *     path="/api/cart",
     *     tags={"Cart"},
     *     summary="Update NFT in cart",
     *     description="Update the quantity of an NFT in the user's cart. If quantity becomes 0, remove the NFT from the cart. <br> Author: Tan",
     *     operationId="updateNFTInCart",
     *     security={{"user-session":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nft_id", type="string", example="9c0a09a4-6767-43a3-8b86-3f22cfda459c", description="ID of the NFT"),
     *             @OA\Property(property="quantity", type="integer", example=1, description="New quantity of the NFT in the cart")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="NFT updated in cart successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Update nft in cart successfully"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="NFT update in cart failed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="An error occurred while updating the NFT in the cart.")
     *         )
     *     )
     * )
     */

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }


    public function __invoke(UpdateNFTInCartRequest $request)
    {
        try {
            $userId = $request->user->id;
            $nftId = $request->input('nft_id');
            $quantity = $request->input('quantity');

            $isUpdated = $this->cartService->UpdateNFTInCart($userId, $nftId, $quantity);
            if ($isUpdated) {
                return $this->success("Update nft in cart successfully", null, 200);
            } else {
                return $this->failMure("Update nft in cart faired", 400);
            }
        } catch (\Throwable $th) {
            return $this->failure("An error occurred while updating the NFT in the cart.", 500);
        }
    }
}

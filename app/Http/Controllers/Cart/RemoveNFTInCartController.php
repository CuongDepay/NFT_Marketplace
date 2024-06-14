<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Services\Cart\CartService;
use Illuminate\Http\Request;

class RemoveNFTInCartController extends Controller
{
    /**
     * Class constructor.
     */
    private CartService $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

     /**
     * @OA\Delete(
     *     path="/api/cart/{nft_id}",
     *     tags={"Cart"},
     *     summary="Remove NFT from cart",
     *     description="Remove an NFT from the user's cart. <br>Author: Tan",
     *     operationId="removeNFTFromCart",
     *     security={{"user-session":{}}},
     *     @OA\Parameter(
     *         name="nft_id",
     *         in="path",
     *         required=true,
     *         description="ID of the NFT to remove from cart",
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="NFT removed from cart successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="NFT removed from cart successfully"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="NFT removal from cart failed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="An error occurred while removing the NFT from the cart.")
     *         )
     *     )
     * )
     */

    public function __invoke(Request $request, $nftId)
    {
        try {
            $userId = $request->user->id;

            $isRemoved = $this->cartService->removeNFTInCart($userId, $nftId);

            if ($isRemoved) {
                return $this->success("NFT removed in cart successfully", [], 200);
            } else {
                return $this->failure("NFT removal from cart failed", 400);
            }
        } catch (\Throwable $th) {
            return $this->failure("An error occurred while removing the NFT from the cart.", 500);
        }
    }
}

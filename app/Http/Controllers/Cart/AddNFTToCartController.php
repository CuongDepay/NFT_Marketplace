<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddNFTToCartRequest;
use App\Http\Services\Cart\CartService;

class AddNFTToCartController extends Controller
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
     * @OA\Post(
     *     path="/api/cart",
     *     tags={"Cart"},
     *     summary="Add NFT to cart",
     *     description="Add an NFT to the user's cart or update the quantity if it already exists.<br>Author: Tan",
     *     operationId="addNFTToCart",
     *     security={{"user-session":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nft_id", type="string", example="9c0a09a4-6767-43a3-8b86-3f22cfda459c", description="ID of the NFT"),
     *             @OA\Property(property="quantity", type="integer", example=1, description="Quantity of the NFT to add")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="NFT added to cart successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="NFT added to cart successfully"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="NFT add to cart failed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object", example={"nft_id": {"The nft_id field is required."}, "quantity": {"The quantity field is required."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="An error occurred while adding the NFT to the cart.")
     *         )
     *     )
     * )
     */

    public function __invoke(AddNFTToCartRequest $request)
    {
        try {
            $userId = $request->user->id;
            $nftId = $request->input('nft_id');
            $quantity = $request->input('quantity');

            $isAdded = $this->cartService->addNFTToCart($userId, $nftId, $quantity);

            if ($isAdded) {
                return $this->success("NFT added to cart successfully", null, 201);
            } else {
                return $this->failure("An error occurred while adding the NFT to the cart.", 500);
            }
        } catch (\Throwable $th) {
            return $this->failure("An error occurred while adding the NFT in the cart.", 500);
        }
    }
}

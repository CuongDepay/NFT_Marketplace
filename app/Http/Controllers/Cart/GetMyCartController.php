<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Resources\Cart\CartDetailResource;
use App\Http\Services\Cart\CartService;
use Illuminate\Http\Request;

class GetMyCartController extends Controller
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    /**
     * @OA\Get(
     *     path="/api/cart",
     *     tags={"Cart"},
     *     summary="Get my cart",
     *     description="Get list NFT in cart. <br>Author: Duc Huy",
     *     operationId="getNFTInCart",
     *     security={{"user-session":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Get list NFT in cart successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Get list NFT in cart successfully"),
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
     *             @OA\Property(property="error", type="string", example="An error occurred while get list NFT from the cart.")
     *         )
     *     )
     * )
     */

    public function __invoke(Request $request)
    {
        $userId = $request->user->id;
        $nftInCart = $this->cartService->getListNFTInCart($userId);

        return $this->success("Get list NFT in cart succesfull", CartDetailResource::collection($nftInCart));
    }
}

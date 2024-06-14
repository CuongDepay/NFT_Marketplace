<?php

namespace App\Http\Controllers\Favourite;

use App\Http\Controllers\Controller;
use App\Http\Requests\Favourite\AddNFTtoFavourite;
use App\Http\Services\Favourite\FavouriteService;
use Illuminate\Http\Request;

class RemoveFavouriteController extends Controller
{
    private FavouriteService $favouriteService;

    public function __construct(FavouriteService $favouriteService)
    {
        $this->favouriteService = $favouriteService;
    }

    /**
     * @OA\Delete(
     *     path="/api/favourites/{nftId}",
     *     tags={"Favourite"},
     *     summary="Remove NFT from Favourite",
     *     description="Remove an NFT from the user's favourite. <br>Author: Cuong",
     *     operationId="removeFavourite",
     *     security={{"user-session":{}}},
     *     @OA\Parameter(
     *         name="nftId",
     *         in="path",
     *         required=true,
     *         description="ID of the NFT to remove from favourite",
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Unfavourite NFT successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unfavourite NFT successfully"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Remove Favourite failed")
     *         )
     *     )
     * )
     */


    public function __invoke(Request $request, $nftId)
    {
        try {
            $data["nft_id"] = $nftId;
            $data["user_id"] = $request->user->id;
            $isExist = $this->favouriteService->isExistFavourite($data);
            if (!$isExist) {
                return $this->failure("Remove Favourite failed", 400);
            }
            $this->favouriteService->deleteFavourite($data);
            return $this->success("Unfavourite NFT successfully", null, 200);
        } catch (\Throwable $th) {
            return $this->failure("Error", 500);
        }
    }
}

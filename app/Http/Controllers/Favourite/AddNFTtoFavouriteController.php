<?php

namespace App\Http\Controllers\Favourite;

use App\Http\Controllers\Controller;
use App\Http\Requests\Favourite\AddNFTtoFavourite;
use App\Http\Services\Favourite\FavouriteService;
use Illuminate\Http\Request;

class AddNFTtoFavouriteController extends Controller
{
    private FavouriteService $favouriteService;

    public function __construct(FavouriteService $favouriteService)
    {
        $this->favouriteService = $favouriteService;
    }

    /**
     * @OA\Post(
     *     path="/api/favourites",
     *     tags={"Favourite"},
     *     summary="Add NFT to Favourite",
     *     description="Add an NFT to the user's Favourite",
     *     operationId="addNFTtoFavourite",
     *     security={{"user-session":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nft_id", type="string", example="9c0a09a4-6767-43a3-8b86-3f22cfda459c", description="ID of the NFT"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="NFT added to Favourite successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="NFT added to Favourite successfully"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Unfavourite NFT successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unfavourite NFT successfully"),
     *              @OA\Property(property="data", type="object", nullable=true)
     *          )
     *      ),
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
     *             @OA\Property(property="error", type="string", example="Error")
     *         )
     *     )
     * )
     */


    public function __invoke(AddNFTtoFavourite $request)
    {
        $data = $request->validated();
        $data["user_id"] = $request->user->id;

        $isExist = $this->favouriteService->isExistFavourite($data);

        if ($isExist) {
            $this->favouriteService->deleteFavourite($data);
            return $this->success("Unfavourite NFT successfully", null, 200);
        } else {
            $item = $this->favouriteService->addNFTtoFavourite($data);
            return $this->success("Add NFT to Favourite successfully", null, 201);
        }
    }
}

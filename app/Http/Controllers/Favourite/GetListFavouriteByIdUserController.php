<?php

namespace App\Http\Controllers\Favourite;

use App\Http\Controllers\Controller;
use App\Http\Resources\NFT\NFTInfoResource;
use App\Http\Services\Favourite\FavouriteService;
use Illuminate\Http\Request;

class GetListFavouriteByIdUserController extends Controller
{
    private FavouriteService $favouriteService;

    public function __construct(FavouriteService $favouriteService)
    {
        $this->favouriteService = $favouriteService;
    }

    /**
     * @OA\Get(
     *     path="/api/favourites/{userId}",
     *     summary="Get List Favourite",
     *     tags={"Favourite"},
     *     description="Retrieve a list of favorite.",
     *     operationId="getListFavourite",
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID of user",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Optional parameter to specify the page number.",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Get list favourite successfull",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Get list favourite successfully"),
     *             @OA\Property(property="data", type="object", example="{}", description="List Favourite Information"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Favourite not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No favourites found for this user")
     *         )
     *     )
     * )
     */

    public function __invoke(Request $request, $userId)
    {
        $favourite = $this->favouriteService->getListFavouriteByIdUser($userId);
        if ($favourite->isEmpty()) {
            return response()->json([
                'message' => 'No favourites found for this user',
            ], 404);
        }
        return $this->success("Get list Favourite successfully", [
            'nfts_favourite' => $favourite->items(),
            'meta' => [
                'current_page' => $favourite->currentPage(),
                'last_page' => $favourite->lastPage(),
                'per_page' => $favourite->perPage(),
                'total' => $favourite->total(),
                'next_page_url' => $favourite->nextPageUrl()
            ]
        ], 200);
    }
}

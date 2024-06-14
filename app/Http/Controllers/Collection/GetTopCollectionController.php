<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use App\Http\Services\Collection\CollectionService;
use App\Http\Services\NFTs\NFTService;
use App\Http\Services\Users\UserService;
use Illuminate\Http\Request;

class GetTopCollectionController extends Controller
{
    private CollectionService $collectionService;

    private UserService $userService;

    private NFTService $nftService;

    public function __construct(CollectionService $collectionService, UserService $userService, NFTService $nftService)
    {
        $this->collectionService = $collectionService;
        $this->userService = $userService;
        $this->nftService = $nftService;
    }

    /**
     * @OA\Get(
     *     path="/api/collections/top",
     *     summary="Get list top collection",
     *     tags={"Collections"},
     *     description="Retrieve a list top collections. <br>Author: Duc Huy",
     *     operationId="getTopCollection",
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         description="Start date for filtering in Y-m-d H:i:s format",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             format="date-time"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         description="End date for filtering in Y-m-d H:i:s format",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             format="date-time"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Get list top collections successfull",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Get list collection successfully"),
     *             @OA\Property(property="data", type="object", example="{}", description="List Collection Information"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Collection not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Please enter valid category ID")
     *         )
     *     )
     * )
     */

    public function __invoke(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        return $this->success("Get top collection successful", ['top_collections' => $this->collectionService->getTopCollections($from, $to), "total_user" => $this->userService->countALlUser(), "total_nft" => $this->nftService->countAllNFT()]);
    }
}
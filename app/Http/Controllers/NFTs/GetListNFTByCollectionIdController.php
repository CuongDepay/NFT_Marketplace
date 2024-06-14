<?php

namespace App\Http\Controllers\NFTs;

use App\Http\Controllers\Controller;
use App\Http\Resources\NFT\NFTInfoResource;
use App\Http\Services\Collection\CollectionService;
use App\Http\Services\NFTs\NFTService;
use App\Models\NFT;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GetListNFTByCollectionIdController extends Controller
{
    private NFTService $nftService;

    private CollectionService $collectionService;

    public function __construct(NFTService $nftService, CollectionService $collectionService)
    {
        $this->nftService = $nftService;
        $this->collectionService = $collectionService;
    }
    /**
     * @OA\Get(
     * path="/api/collections/{collectionId}/nfts",
     * summary="Get list NFT by collectionId and filter",
     * tags={"NFT APIs"},
     * description="Get list NFT by collectionId and filter.<br>Author: Duc Huy",
     * operationId="getListNFT",
     * security={{"user-session":{}}},
     *  @OA\Parameter(
     *     name="collectionId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="string",
     *         example="9c002343-7853-454b-b489-a5d10635ea94",
     *     ),
     *     description="Id of collection",
     * ),
     * @OA\Parameter(
     *     name="min_price",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *         type="float",
     *         example="10.0",
     *     ),
     *     description="Min price of NFT",
     * ),
     * @OA\Parameter(
     *     name="max_price",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *         type="float",
     *         example="20.0",
     *     ),
     *     description="Max price of NFT",
     * ),
     *  @OA\Parameter(
     *     name="status",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *         type="string",
     *         example="ALL",
     *     ),
     *     description="Max price of NFT",
     * ),
     * @OA\Parameter(
     *     name="content",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *         type="string",
     *         example="content",
     *     ),
     *     description="Content for search",
     * ),
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *         type="number",
     *         example="1",
     *     ),
     *     description="Page",
     * ),
     * @OA\Parameter(
     *     name="page_size",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *         type="number",
     *         example="12",
     *     ),
     *     description="Page",
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Get list NFT successful",
     *     @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Success message", description="Success message"),
     *         @OA\Property(property="data", type="object", example="{}", description="User information"),
     *     ),
     * ),
     * @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthorized", description="Error message"),
     *     )
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Collection not found",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string",example="Account is already logged in. Please log out first!", description="Error message"),
     *     )
     *   ),
     * ),
     */

    public function __invoke($collectionId, Request $request)
    {
        $collection = $this->collectionService->findById($collectionId);
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');

        $nfts = $this->nftService->getListNFTByCollectionIdAndFilterWithPaginate(
            $collectionId,
            $minPrice,
            $maxPrice,
            $request->query('status') == "ON_SALE" ? Carbon::now() : null,
            $request->query('content'),
            $request->query('page_size')
        );

        return $this->success("Get list NFT successfull", [
            'data' => NFTInfoResource::collection($nfts),
            'meta' => [
                'current_page' => $nfts->currentPage(),
                'from' => $nfts->firstItem(),
                'last_page' => $nfts->lastPage(),
                'path' => $nfts->path(),
                'per_page' => $nfts->perPage(),
                'to' => $nfts->lastItem(),
                'total' => $nfts->total(),
            ]
        ]);
    }
}

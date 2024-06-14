<?php

namespace App\Http\Controllers\NFTs;

use App\Http\Controllers\Controller;
use App\Http\Resources\NFT\GetTopNFTResource;
use App\Http\Services\NFTs\NFTService;
use Illuminate\Http\Request;

class GetTopNFTController extends Controller
{
    //
    private NFTService $nftService;

    /**
     * Class constructor.
     */
    public function __construct(NFTService $nftService)
    {
        $this->nftService = $nftService;
    }

    /**
 * @OA\Get(
 *     path="/api/nfts/get-top",
 *     summary="Get Top NFTs",
 *     tags={"NFT APIs"},
 *     description="Retrieve a list of top NFTs.",
 *     operationId="getTopNFT",
 *     @OA\Parameter(
 *         name="category_id",
 *         in="query",
 *         description="Optional parameter to filter NFTs by category ID.",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Get top NFTs successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Get top NFTs successfully"),
 *             @OA\Property(property="data", type="string"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="NFTs not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="No NFTs found for the specified category ID")
 *         )
 *     )
 * )
 */

    public function __invoke(Request $request)
    {
        $categoryId = $request->input("category_id");
        $topNFTs = $this->nftService->getTopNFT($categoryId);

        return $this->success("Get top nft", GetTopNFTResource::collection($topNFTs));
    }
}

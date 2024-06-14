<?php

namespace App\Http\Controllers\NFTs;

use App\Http\Controllers\Controller;
use App\Http\Resources\NFT\NFTInfoResource;
use App\Http\Services\NFTs\NFTService;

class GetDetailNFTController extends Controller
{
    private NFTService $nftService;

    public function __construct(NFTService $nftService)
    {
        $this->nftService = $nftService;
    }
    /**
     * @OA\Get(
     * path="/api/nfts/{nftId}",
     * summary="Get info detail of NFT",
     * tags={"NFT APIs"},
     * description="Get info detail of NFT.<br>Author: Duc Huy",
     * operationId="getDetailNFT",
     * security={{"user-session":{}}},
     *  @OA\Parameter(
     *     name="nftId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="string",
     *         example="9c02797b-747f-4836-9346-7f7787f624f5",
     *     ),
     *     description="Id of NFT",
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Login successful",
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
     *     description="NFT not found",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string",example="Account is already logged in. Please log out first!", description="Error message"),
     *     )
     *   ),
     * ),
     */

    public function __invoke($nftId)
    {
        $nft = $this->nftService->findById($nftId);
        return $this->success("Get detail NFT succesfull", new NFTInfoResource($nft));
    }
}

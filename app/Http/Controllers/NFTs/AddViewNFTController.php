<?php

namespace App\Http\Controllers\NFTs;

use App\Http\Controllers\Controller;
use App\Http\Services\NFTs\NFTService;

class AddViewNFTController extends Controller
{
    private NFTService $nftService;

    public function __construct(NFTService $nftService)
    {
        $this->nftService = $nftService;
    }

    /**
     * Add view NFT
     *
     * @OA\Post(
     *      path="/api/nfts/{nftId}/views",
     *      summary="Add view NFT",
     *      tags={"NFT APIs"},
     *      description="Description: Add view NFT. <br> Author: Duc Huy",
     *      operationId="addViewNFT",
     *      security={{"user-session":{}}},
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
     *      @OA\Response(
     *          response=200,
     *          description="Add view NFT successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Add view NFT successfull"),
     *              @OA\Property(property="data", type="object", example="{}", description="Information about the created NFT")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="NFT not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false, description="Indicates if NFT creation failed"),
     *              @OA\Property(property="message", type="string", example="Bad request", description="Error message")
     *          )
     *      ),
     * )
     */

    public function __invoke($nftId)
    {
        $this->nftService->addViewNFT($nftId);
        return $this->success("Add view successfull");
    }
}

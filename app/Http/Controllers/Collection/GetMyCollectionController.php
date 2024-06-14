<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collection\MyCollectionResource;
use App\Http\Services\Collection\CollectionService;
use Illuminate\Http\Request;

class GetMyCollectionController extends Controller
{
    private CollectionService $collectionService;

    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    /**
     * @OA\Get(
     *     path="/api/collections",
     *     tags={"Collections"},
     *     summary="Get my collections",
     *     description="Get list NFT in cart. <br>Author: Cuong",
     *     operationId="getMyCollections",
     *     security={{"user-session":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Get my collection successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="get my collection successfully"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="")
     *         )
     *     )
     * )
     */

    public function __invoke(Request $request)
    {
        $userId = $request->user()->id;
        $collections = $this->collectionService->getMyCollection($userId);
        return $this->success("Get my collection successfully", MyCollectionResource::collection($collections), 200);
    }
}

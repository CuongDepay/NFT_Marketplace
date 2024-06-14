<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collection\CollectionResource;
use App\Http\Services\Collection\CollectionService;
use Illuminate\Http\Request;

class GetCollectionDetailController extends Controller
{
    private CollectionService $collectionService;

    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    /**
     * @OA\Get(
     * path="/api/collections/{collectionId}",
     * summary="Get info detail of Collection",
     * tags={"Collections"},
     * description="Get info detail of Collection.<br>Author: Cuong",
     * operationId="getDetailCollection",
     *  @OA\Parameter(
     *     name="collectionId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="string",
     *         example="9c02797b-747f-4836-9346-7f7787f624f5",
     *     ),
     *     description="ID of Collection",
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Get collection detail successful",
     *     @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Success message", description="Success message"),
     *         @OA\Property(property="data", type="object", example="{}", description="User information"),
     *     ),
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Collection not found",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string",example="Please enter valic collection_id", description="Error message"),
     *     )
     *   ),
     * ),
     */


    public function __invoke($collectionId)
    {
        $collection = $this->collectionService->findById($collectionId);
        return $this->success("Get Collection Detail Successfully", $collection);
    }
}

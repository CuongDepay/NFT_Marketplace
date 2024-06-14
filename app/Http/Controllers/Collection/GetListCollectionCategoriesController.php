<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use App\Http\Services\Collection\CollectionCategoriesService;
use Illuminate\Http\Request;

class GetListCollectionCategoriesController extends Controller
{
    private CollectionCategoriesService $collectionCategoriesService;

    public function __construct(CollectionCategoriesService $collectionCategoriesService)
    {
        $this->collectionCategoriesService = $collectionCategoriesService;
    }

    /**
     * @OA\Get(
     * path="/api/collection-categories",
     * summary="Get all collection categories",
     * tags={"Collection Categories"},
     * description="Get all Collection categories.<br>Author: Cuong",
     * operationId="getAllCollectionCategories",
     * @OA\Response(
     *     response=200,
     *     description="Get all collection categories successful",
     *     @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Success message", description="Success message"),
     *         @OA\Property(property="data", type="object", example="{}", description="User information"),
     *     ),
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Not Found",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Not Found", description="Error message"),
     *     )
     * ),
     * ),
     */


    public function __invoke()
    {
        $collectionCategories = $this->collectionCategoriesService->getAllCollectionCategories();
        return $this->success("Get collection categories successfull", $collectionCategories);
    }
}

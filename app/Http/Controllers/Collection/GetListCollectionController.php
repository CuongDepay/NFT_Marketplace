<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use App\Http\Services\Collection\CollectionService;
use Illuminate\Http\Request;

class GetListCollectionController extends Controller
{
    private CollectionService $collectionService;

    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }
    /**
     * @OA\Get(
     *     path="/api/collections/collectionsList",
     *     summary="Get List Collection",
     *     tags={"Collections"},
     *     description="Retrieve a list of collections.",
     *     operationId="getListCollection",
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="query",
     *         description="Optional parameter to filter collections by category ID.",
     *         required=false,
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
     *     @OA\Parameter(
     *         name="searchText",
     *         in="query",
     *         description="Optional parameter to specify the searchText.",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             default=""
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Get list collections successfull",
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
        $categoryId = $request->input('categoryId');
        $searchText = $request->input('searchText');
        $collections = $this->collectionService->getListCollection($categoryId, $searchText);

        return $this->success("Get list collection successfully", [
            'current_page' => $collections->currentPage(),
            'collections' => $collections->items(),
            'meta' => [
                'per_page' => $collections->perPage(),
                'total' => $collections->total(),
                'last_page' => $collections->lastPage(),
                'first_page_url' => $collections->url(1),
                'last_page_url' => $collections->url($collections->lastPage()),
            ]
        ]);
    }
}

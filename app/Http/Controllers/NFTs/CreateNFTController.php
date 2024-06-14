<?php

namespace App\Http\Controllers\NFTs;

use App\Helpers\MessageConstant;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\NFTs\CreateNFTRequest;
use App\Http\Resources\Collection\CollectionResource;
use App\Http\Resources\NFT\NFTInfoResource;
use App\Http\Services\Collection\CollectionService;
use App\Http\Services\NFTs\NFTService;
use App\Models\NotificationCategory;
use App\Models\UserRelationship;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateNFTController extends Controller
{
    private NFTService $nftService;

    private CollectionService $collectionService;

    public function __construct(NFTService $nftService, CollectionService $collectionService)
    {
        $this->nftService = $nftService;
        $this->collectionService = $collectionService;
    }

    /**
     * Create a new collection.
     *
     * @OA\Post(
     *      path="/api/nfts",
     *      summary="Create a new NFT",
     *      tags={"NFT APIs"},
     *      description="Description: Creates a new NFT with specified details. <br> Author: Duc Huy",
     *      operationId="createNFT",
     *      security={{"user-session":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Collection information",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"title", "description", "starting_date", "expiration_date", "price", "collection_id", "image_url", "quantity"},
     *                  @OA\Property(property="title", type="string", example="Pokemon"),
     *                  @OA\Property(property="price", type="number", format="float", example=19.99),
     *                  @OA\Property(property="quantity", type="number", format="number", example=100),
     *                  @OA\Property(property="description", type="string", example="A detailed description of the NFT"),
     *                  @OA\Property(property="starting_date", type="timestamp", format="date", example="2024-04-25 08:31:52.000"),
     *                  @OA\Property(property="expiration_date", type="timestamp", format="date", example="2024-04-25 08:31:52.000"),
     *                  @OA\Property(property="image_url", type="file", format="url", example="https://example.com/logo.jpg", description="URL of the collection's logo image (must be JPG, PNG, or JPEG)"),
     *                  @OA\Property(property="collection_id", type="string", example="9be3f4b2-3b68-43fe-85e9-91003bc08c1b"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="NFT created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Collection created"),
     *              @OA\Property(property="data", type="object", example="{}", description="Information about the created NFT")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false, description="Indicates if NFT creation failed"),
     *              @OA\Property(property="message", type="string", example="Bad request", description="Error message")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false, description="Indicates if NFT creation failed"),
     *              @OA\Property(property="message", type="string", example="Bad request", description="Error message")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false, description="Indicates if NFT creation failed"),
     *              @OA\Property(property="message", type="string", example="The given data was invalid.", description="Error message"),
     *              @OA\Property(property="errors", type="object", example="{}", description="Validation errors")
     *          )
     *      )
     * )
     */

    public function __invoke(CreateNFTRequest $request)
    {
        $user = $request->user();
        if ($request->starting_date >= $request->expiration_date) {
            throw new BadRequestHttpException(MessageConstant::EXPIRATION_DATE_MUST_BE_LARGER_THAN_STARTING_DATE);
        }
        $collection = $this->collectionService->findByIdAndUserId($request->collection_id, $request->user->id);
        $nft = $this->nftService->createNFT($request, $request->collection_id);

        $listUser =  UserRelationship::where("user_id",$request->user()->id)->get();

        foreach ($listUser as $key => $value) {
            $data = NotificationHelper::createNotificationData(
                "New Item By ".$user->name,
                "Hello",
                NotificationCategory::all()[3]->id,
                $user,
                $value->follower_id,
            );
            NotificationHelper::SendNotification($data);
        }

        $nftWithPriceHistories = $this->nftService->findById($nft->id);
        return $this->success("Create NFT successfull", ["nft" => new NFTInfoResource($nftWithPriceHistories), "collection" => new CollectionResource($collection)]);
    }
}

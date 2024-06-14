<?php

namespace App\Http\Controllers\Collection;

use App\Helpers\MessageConstant;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Collection\StoreCollectionRequest;
use App\Http\Services\Collection\CollectionService;
use App\Models\NotificationCategory;
use App\Models\UserRelationship;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CollectionController extends Controller
{
    private CollectionService $collectionService;

    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    /**
     * Create a new collection.
     *
     * @OA\Post(
     *      path="/api/collections",
     *      summary="Create a new collection",
     *      tags={"Collections"},
     *      description="Description: Creates a new collection with specified details. <br> Author: Cuong",
     *      operationId="createCollection",
     *      security={{"user-session":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Collection information",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"url", "description", "starting_date", "name", "expiration_date", "price", "category_id", "logo_image_url", "featured_image_url", "cover_image_url"},
     *                  @OA\Property(property="name", type="string", example="your-collection-name"),
     *                  @OA\Property(property="url", type="string", example="your-collection-url"),
     *                  @OA\Property(property="description", type="string", example="A detailed description of the collection"),
     *                  @OA\Property(property="starting_date", type="string", format="date", example="10-05-2024 00:00:00"),
     *                  @OA\Property(property="expiration_date", type="string", format="date", example="10-06-2024 00:00:00"),
     *                  @OA\Property(property="price", type="number", format="float", example=19.99),
     *                  @OA\Property(property="logo_image_url", type="file", format="url", example="https://example.com/logo.jpg", description="URL of the collection's logo image (must be JPG, PNG, or JPEG)"),
     *                  @OA\Property(property="featured_image_url", type="file", format="url", example="https://example.com/featured.jpg", description="URL of the collection's featured image (must be JPG, PNG, or JPEG)"),
     *                  @OA\Property(property="cover_image_url", type="file", format="url", example="https://example.com/cover.jpg", description="URL of the collection's cover image (must be JPG, PNG, or JPEG)"),
     *                  @OA\Property(property="category_id", type="string", example="")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Collection created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Collection created"),
     *              @OA\Property(property="data", type="object", example="{}", description="Information about the created collection")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false, description="Indicates if collection creation failed"),
     *              @OA\Property(property="message", type="string", example="Bad request", description="Error message")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false, description="Indicates if collection creation failed"),
     *              @OA\Property(property="message", type="string", example="The given data was invalid.", description="Error message"),
     *              @OA\Property(property="errors", type="object", example="{}", description="Validation errors")
     *          )
     *      )
     * )
     */


    public function storeCollection(StoreCollectionRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $data["user_id"] = $user->id;
        if ($request->starting_date >= $request->expiration_date) {
            throw new BadRequestHttpException(MessageConstant::EXPIRATION_DATE_MUST_BE_LARGER_THAN_STARTING_DATE);
        }
        $collection = $this->collectionService->createCollection($data);

        $listUser =  UserRelationship::where("user_id",$request->user()->id)->get();

        foreach ($listUser as $key => $value) {
            $data = NotificationHelper::createNotificationData(
                "New Collection By ".$user->name,
                "Hello",
                NotificationCategory::all()[3]->id,
                $user,
                $value->follower_id,
            );
            NotificationHelper::SendNotification($data);
        }

        return $this->success("Create successfully", $collection, 200);
    }
}

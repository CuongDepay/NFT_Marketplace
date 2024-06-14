<?php

namespace App\Http\Controllers\Checkout;

use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\CheckoutRequest;
use App\Http\Services\Checkout\CheckoutService;
use App\Models\Collection;
use App\Models\NFT;
use App\Models\NotificationCategory;
use App\Models\User;

class CheckoutController extends Controller
{
    private CheckoutService $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    /**
     * Create a new checkout.
     *
     * @OA\Post(
     *      path="/api/checkout",
     *      summary="Create a new checkout",
     *      tags={"Checkout"},
     *      description="Description: Creates a new checkout with specified details. <br> Author: Cuong",
     *      operationId="createCheckout",
     *      security={{"user-session":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="checkout information",
     *          @OA\JsonContent(
     *                  @OA\Property(property="company_name", type="string", example="Name"),
     *                  @OA\Property(property="code", type="string", example=""),
     *                  @OA\Property(property="shipping", type="string", example="free"),
     *                  @OA\Property(property="tax", type="string", format="float", example="free"),
     *                  @OA\Property(property="order_notes", type="string", example="Note"),
     *                  @OA\Property(
     *                          property="items",
     *                          type="array",
     *                          description="List of NFT items in the order",
     *                          @OA\Items(
     *                              @OA\Property(property="nft_id", type="string", description="ID of the NFT item"),
     *                              @OA\Property(property="quantity", type="integer", description="Quantity of the NFT item")
     *                          )
     *                 ),
     *              )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Checkout created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Checkout created"),
     *              @OA\Property(property="data", type="object", example="{}", description="Information about the created checkout")
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
     *              @OA\Property(property="success", type="boolean", example=false, description="Indicates if checkout creation failed"),
     *              @OA\Property(property="message", type="string", example="The given data was invalid.", description="Error message"),
     *              @OA\Property(property="errors", type="object", example="{}", description="Validation errors")
     *          )
     *      )
     * )
     */

    public function __invoke(CheckoutRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $data['user_id'] = $user->id;
        $order = $this->checkoutService->createCheckout($data);


        // return $data["items"][0]['nft_id'];
        $nft = NFT::find($data["items"][0]['nft_id']);
        $collection = Collection::find($nft->collection_id);
        $data = NotificationHelper::createNotificationData(
            "New Purchases By ".$user->name,
            "New Purchases",
            NotificationCategory::all()[0]->id,
            $user,
            $collection->user_id,
        );
        NotificationHelper::SendNotification($data);

        $seller = User::find($collection->user_id);
        $sales = NotificationHelper::createNotificationData(
            "New Sales By ".$seller->name,
            "New Sales ",
            NotificationCategory::all()[1]->id,
            $seller,
            $user->id,
        );
        NotificationHelper::SendNotification($sales);
        return $this->success("Checkout Successfully", "", 201);
    }
}

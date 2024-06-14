<?php

namespace App\Http\Controllers\Notifications\NotificationCategories;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notifications\NotificationCategory\NotificationCategoryResource;
use App\Http\Services\Notifications\NotificationCategoryService;

class GetNotificationCategories extends Controller
{
    private NotificationCategoryService $notificationService;

    public function __construct(NotificationCategoryService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * @OA\Get(
     * path="/api/notification-categories",
     * summary="Get all notification categories",
     * tags={"Notification categories"},
     * description="Get all notification categories.<br>Author: Tan",
     * operationId="getNotificationCategories",
     * security={{"user-session":{}}},
     * @OA\Response(
     *     response=200,
     *     description="Get all notification categories successful",
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
     * ),
     */

    public function __invoke()
    {
        $getAllNotiCategories = $this->notificationService->getAllNotificationCategories();

        if ($getAllNotiCategories) {
            $response = NotificationCategoryResource::collection($getAllNotiCategories);
            return $this->success("Get all notification categories", $response);
        }

        return $this->failure("Notification categories not found", 404);
    }
}

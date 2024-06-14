<?php

namespace App\Http\Controllers\Notifications\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notifications\Notification\NotificationResource;
use App\Http\Services\Notifications\NotificationService;
use Illuminate\Http\Request;

class GetListNotification extends Controller
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * @OA\Get(
     * path="/api/notifications",
     * summary="Get all notification by categories",
     * tags={"Notification"},
     * description="Get all notification by category or all.<br>Author: Tan",
     * operationId="getAllNotification",
     * security={{"user-session":{}}},
    * @OA\Parameter(
    *     name="notification-category-id",
    *     in="query",
    *     required=false,
    *     @OA\Schema(type="uuid"),
    *     description="ID of the notification category",
    *     example = "9bf4a378-a1f6-46c1-83df-be1006b28a1c",
    * ),
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
     * @OA\Response(
     *     response=404,
     *     description="No notifications found",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="No notifications found", description="Error message"),
     *     )
     * ),
     * ),
     */

    public function __invoke(Request $request)
    {
        $listNotification = $this->notificationService->getAllNotificationsByCategory($request);
        if ($listNotification->isEmpty()) {
            return $this->failure("No notifications found", 404);
        };
        return $this->success("Get All List notification", NotificationResource::collection($listNotification));
    }
}

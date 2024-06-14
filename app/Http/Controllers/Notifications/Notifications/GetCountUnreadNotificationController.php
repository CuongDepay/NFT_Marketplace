<?php

namespace App\Http\Controllers\Notifications\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Services\Notifications\NotificationService;
use Illuminate\Http\Request;

class GetCountUnreadNotificationController extends Controller
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * @OA\Get(
     *     path="/api/notifications/count-unread",
     *     summary="Get unread notifications count",
     *     tags={"Notification"},
     *     description="Get the count of unread notifications for the authenticated user.",
     *     operationId="getUnreadNotificationsCount",
     *     security={{"user-session":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Count of unread notifications",
     *         @OA\JsonContent(
     *             @OA\Property(property="count", type="integer", example=5),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *         )
     *     )
     * )
     */
    public function __invoke(Request $request)
    {
        $userId = $request->user()->id;
        $unreadCount = $this->notificationService->getUnreadNotificationsCount($userId);
        return $this->success("Get count unread notification successfully", [
            'countUnread' => $unreadCount
        ]);
    }
}

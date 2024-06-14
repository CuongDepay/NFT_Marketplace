<?php

namespace App\Http\Controllers\Notifications\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Services\Notifications\NotificationService;
use Illuminate\Http\Request;

class MarkAllReadNotificationController extends Controller
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * @OA\Put(
     *     path="/api/notifications/mark-all-read",
     *     summary="Mark all notifications as read",
     *     tags={"Notification"},
     *     description="Mark all notifications as read for the authenticated user.<br>Author: Tan",
     *     operationId="markAllReadNotification",
     *     security={{"user-session":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Mark all notifications as read successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Mark all read notification successfully", description="Success message"),
     *             @OA\Property(property="data", type="object", example="{}", description="Additional data if any"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Bad request", description="Error message"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized", description="Error message"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal server error", description="Error message"),
     *         )
     *     )
     * )
     */
    public function __invoke(Request $request)
    {
        if ($this->notificationService->markReadAllNotification($request->user())) {
            return $this->success("Mark all read notification successfully", [], 200);
        }
        return $this->failure("Mark all read notification faired", 400);
    }
}

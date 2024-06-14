<?php

namespace App\Http\Controllers\Notifications\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Services\Notifications\NotificationService;
use Illuminate\Http\Request;

class MarkReadNotificationIdController extends Controller
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * @OA\Put(
     *     path="/api/notifications/{id}",
     *     summary="Mark a notification as read",
     *     tags={"Notification"},
     *     description="Mark a specific notification as read for the authenticated user.<br>Author: Tan",
     *     operationId="markNotificationAsRead",
     *     security={{"user-session":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the notification to mark as read",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notification marked as read successfully or already marked as read",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Notification marked as read successfully", description="Success message"),
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
     *         response=404,
     *         description="Notification not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Notification not found", description="Error message"),
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

    public function __invoke(Request $request, $id)
    {
        $notification = $this->notificationService->getNotificationByIdAndUser($id, $request->user()->id);

        if (!$notification) {
            return $this->failure("Notification not found", 404);
        }

        if ($notification->is_read) {
            return $this->success("Notification is already marked as read");
        }

        $this->notificationService->markAsRead($notification);

        return $this->success("Notification marked as read successfully");
    }
}

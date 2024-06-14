<?php

namespace App\Http\Controllers\Notifications\NotificationSetting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notifications\NotificationSettingRequest;
use App\Http\Services\Notifications\NotificationSettingService;

class SettingNotificationController extends Controller
{
    private NotificationSettingService $notificationService;

    public function __construct(NotificationSettingService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Create a new collection.
     *
     * @OA\Put(
     *      path="/api/notification-setting",
     *      summary="Update a notification setting",
     *      tags={"Notification setting"},
     *      description="Description: Updates a notification setting for the authenticated user. <br> Author: Tan",
     *      operationId="notificationSetting",
     *      security={{"user-session":{}}},
     *      @OA\RequestBody(
     *           @OA\JsonContent(
     *              @OA\Property(property="is_order_confirmation", type="boolean", example=true),
     *              @OA\Property(property="is_new_items", type="number", format="boolean", example=true),
     *              @OA\Property(property="is_new_collections", type="number", format="boolean", example=true),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Notification setting updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Setting Notification successful"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={}, description="Validation errors")
     *          )
     *      )
     * )
     */

    public function __invoke(NotificationSettingRequest $request)
    {
        try {
            $notificationSetting = $request->validated();
            $this->notificationService->updateNotificationSetting($request->user()->id, $notificationSetting);
            return $this->success("Notification setting updated successful");
        } catch (\Exception $e) {
            $this->failure($e->getMessage());
        }
    }
}

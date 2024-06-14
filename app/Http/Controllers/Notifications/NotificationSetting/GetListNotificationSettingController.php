<?php

namespace App\Http\Controllers\Notifications\NotificationSetting;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notifications\NotificationSetting\NotificationSettingResource;
use App\Http\Services\Notifications\NotificationSettingService;
use Illuminate\Http\Request;

class GetListNotificationSettingController extends Controller
{
    private NotificationSettingService $notificationService;

    public function __construct(NotificationSettingService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Create a new collection.
     *
     * @OA\Get(
     *      path="/api/notification-setting",
     *      summary="Get list notification setting",
     *      tags={"Notification setting"},
     *      description="Description: Get list a notification setting for the authenticated user. <br> Author: Tan",
     *      operationId="getListNotificationSetting",
     *      security={{"user-session":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Get list notification setting successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Setting Notification successful"),
     *              @OA\Property(property="data", type="object", example="List notification setting"),
     *          )
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Unauthorized", description="Error message"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Notification setting not found",
     *          @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Unauthorized", description="Notification setting not found"),
     *          )
     *      ),
     * )
     */

    public function __invoke(Request $request)
    {
        $listNotificationSetting = $this->notificationService->getListNotificationSetting($request->user()->id);
        if ($listNotificationSetting) {
            $response = new NotificationSettingResource($listNotificationSetting);
            return $this->success("Get list notification setting successful", $response);
        }
        return $this->failure("Notification setting not found", 404);
    }
}

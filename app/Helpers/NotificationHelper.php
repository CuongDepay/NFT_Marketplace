<?php

namespace App\Helpers;

use App\Events\NotificationEvent;
use App\Http\Resources\Users\UserNotificationEventResource;
use App\Models\User;

class NotificationHelper
{
    public static function createNotificationData($title, $content, $notification_category_id, $sender, $receiverId)
    {
        $receiver = User::all()->find($receiverId);
        $senderResources = new UserNotificationEventResource($sender);
        $receiverResources = new UserNotificationEventResource($receiver);
        return [
            "title" => $title,
            "content" => $content,
            "notification_category_id" => $notification_category_id,
            "sender" => $senderResources,
            "receiver" => $receiverResources
        ];
    }

    public static function SendNotification($dataNotification)
    {
        event(new NotificationEvent($dataNotification));
    }
}

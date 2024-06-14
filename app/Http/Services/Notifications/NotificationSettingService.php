<?php

namespace App\Http\Services\Notifications;

use App\Models\NotificationSetting;

class NotificationSettingService
{
    public function getListNotificationSetting($userId)
    {
        return NotificationSetting::where("user_id", $userId)->first();
    }

    public function updateNotificationSetting($userId, $notificationSetting)
    {
        return NotificationSetting::where("user_id", $userId)->update($notificationSetting);
    }
}

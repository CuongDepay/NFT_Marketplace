<?php

namespace App\Http\Services\Notifications;

use App\Models\NotificationCategory;

class NotificationCategoryService
{
    public function getAllNotificationCategories()
    {
        return NotificationCategory::all();
    }
}

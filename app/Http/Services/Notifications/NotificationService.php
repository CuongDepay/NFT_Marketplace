<?php

namespace App\Http\Services\Notifications;

use App\Models\Notification;

class NotificationService
{
    public function getAllNotificationsByCategory($request)
    {
        $userId = $request->user->id;
        $notificationCategoryId = $request->input("notification-category-id");
        $listNotification = Notification::with(['category', 'sender', 'receiver'])->where("receiver_id", $userId);
        if ($notificationCategoryId) {
            $listNotification =  $listNotification->where("notification_category_id", $notificationCategoryId)->get();
            return $listNotification;
        }
        $listNotification =  $listNotification->get();
        return $listNotification;
    }

    public function markReadAllNotification($user)
    {
        $markAllRead = $user->notifications()->update(["is_read" => true]);
        if ($markAllRead) {
            return true;
        } else {
            return false;
        }
    }

    public function getNotificationByIdAndUser($id, $userId)
    {
        return Notification::where([
            'id' => $id,
            'receiver_id' => $userId
        ])->first();
    }

    public function markAsRead($notification)
    {
        return $notification->update(['is_read' => true]);
    }

    public function getUnreadNotificationsCount($userId)
    {
        return Notification::where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();
    }
}

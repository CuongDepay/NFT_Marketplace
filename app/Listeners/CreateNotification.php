<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotificationEvent $event): void
    {
        try {
            $notificationData = [
                'title' => $event->title,
                'content' => $event->content,
                "notification_category_id" => $event->notification_category_id,
                "sender_id" => $event->sender->id,
                "receiver_id" => $event->receiver->id
            ];

            Notification::create($notificationData);
        } catch (\Throwable $th) {
            Log::error("Error create notification: " . $th->getMessage());
        }
    }
}

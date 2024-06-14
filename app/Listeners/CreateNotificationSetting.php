<?php

namespace App\Listeners;

use App\Models\NotificationSetting;
use Illuminate\Auth\Events\Registered;

class CreateNotificationSetting
{
    /**
     * Create the event listener.
     */
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;
        if ($user) {
            NotificationSetting::create([
                'user_id' => $user->id,
            ]);
        }
    }
}

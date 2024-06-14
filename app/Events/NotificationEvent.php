<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public $title;
    public $content;
    public $notification_category_id;
    public $sender;
    public $receiver;

    public function __construct($dataNotification)
    {
        //
        $this->title = $dataNotification['title'];
        $this->content = $dataNotification["content"];
        $this->notification_category_id = $dataNotification["notification_category_id"];
        $this->sender = $dataNotification['sender'];
        $this->receiver = $dataNotification['receiver'];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('notifications-event-' . $this->receiver->id),
        ];
    }
}

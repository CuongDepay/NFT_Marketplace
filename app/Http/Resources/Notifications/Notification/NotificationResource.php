<?php

namespace App\Http\Resources\Notifications\Notification;

use App\Http\Resources\Notifications\NotificationCategory\NotificationCategoryResource;
use App\Http\Resources\Users\UserNotificationEventResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            'content' => $this->content,
            'is_read' => $this->is_read,
            "category" => new NotificationCategoryResource($this->whenLoaded("category")),
            "sender" => new UserNotificationEventResource($this->whenLoaded('sender')),
            "receiver" => new UserNotificationEventResource($this->whenLoaded('receiver')),
            'created_at' => $this->created_at,
        ];
    }
}

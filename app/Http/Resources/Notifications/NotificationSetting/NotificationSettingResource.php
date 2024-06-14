<?php

namespace App\Http\Resources\Notifications\NotificationSetting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationSettingResource extends JsonResource
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
            "is_order_confirmation" => $this->is_order_confirmation,
            "is_new_items" => $this->is_new_items,
            "is_new_collections" => $this->is_new_collections
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Notification extends Model
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasUuids;
    use SoftDeletes;

    protected $table = "notifications";
    protected $fillable = [
        "content",
        "is_read",
        "title",
        "messages",
        'sender_id',
        'notification_category_id',
        'receiver_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at'
    ];

    public function category()
    {
        return $this->belongsTo(NotificationCategory::class, "notification_category_id");
    }

    public function sender()
    {
        return $this->belongsTo(User::class, "sender_id");
    }


    public function receiver()
    {
        return $this->belongsTo(User::class, "receiver_id");
    }
}

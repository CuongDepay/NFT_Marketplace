<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasUuids;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "users";
    protected $fillable = [
        'name',
        "gender",
        'email',
        "background",
        "avatar",
        "custom_url",
        'password',
        "phone_number",
        "address",
        "introduce",
        'verification_token',
        "verification_token_expires_at",
        "country",
        "state",
        "zip_code",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function followers()
    {
        return $this->belongsToMany(User::class, "user_relationships", "user_id", "follower_id")->whereNull('user_relationships.deleted_at');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'receiver_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, "user_relationships", "follower_id", "user_id")->whereNull('user_relationships.deleted_at');
    }

    public function nfts()
    {
        return $this->hasManyThrough(NFT::class, Collection::class);
    }
}

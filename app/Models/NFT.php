<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class NFT extends Model
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasUuids;
    use SoftDeletes;

    protected $table = "nfts";
    protected $fillable = [
        "starting_date",
        "expiration_date",
        "description",
        "image_url",
        'is_active',
        'view',
        'collection_id',
        'price',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at'
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'nft_id');
    }
}

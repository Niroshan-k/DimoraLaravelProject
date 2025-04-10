<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Advertisement;

class WishListItem extends Model
{
    /** @use HasFactory<\Database\Factories\WishListItemFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'advertisement_id'  
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the advertisement that this wishlist item refers to.
     */
    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
}

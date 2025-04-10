<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Property;
use App\Models\Image;
use App\Models\User;
use App\Models\Inquiry;



class Advertisement extends Model
{
    /** @use HasFactory<\Database\Factories\AdvertisementFactory> */
    use HasFactory;

    protected $fillable = [
        'property_id',
        'seller_id',
        'status'
    ];

    public function seller()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'advertisement_id', 'id');
    }

    public function property()
    {
        return $this->hasOne(Property::class);
    }

    public function wishListItem()
    {
        return $this->hasMany(User::class, 'advertisement_id', 'id');
    }
    public function name()
    {
        return $this->hasMany(Inquiry::class, 'advertisement_id', 'id');
    }
}

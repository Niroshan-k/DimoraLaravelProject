<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Advertisement;
use App\Models\House;
//use App\Models\Land;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;

    protected $fillable = [
        'location',
        'price',
        'type',
        'advertisement_id',  
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function house()
    {
        return $this->hasOne(House::class, 'property_id', 'id');
    }

    // public function land()
    // {
    //     return $this->hasOne(Land::class);
    // }
}

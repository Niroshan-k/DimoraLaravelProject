<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Property;

class House extends Model
{
    /** @use HasFactory<\Database\Factories\HouseFactory> */
    use HasFactory;

    protected $fillable = [
        'bedroom',
        'bathroom',
        'pool',
        'area',
        'parking'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}

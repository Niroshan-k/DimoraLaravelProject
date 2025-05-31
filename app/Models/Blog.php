<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Blog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'blogs';

    protected $fillable = [
        'seller_id',
        'seller_name',
        'title',
        'content',
        'images',
        'created_at',
        'updated_at',
    ];
}

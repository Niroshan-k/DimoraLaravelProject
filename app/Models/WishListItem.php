<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class WishListItem extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'wishlists';
    protected $fillable = ['user_id', 'advertisement_id'];
}

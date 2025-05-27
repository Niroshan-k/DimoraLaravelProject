<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;


class MongoInfo extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'users'; // Replace with your collection
}

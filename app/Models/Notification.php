<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'content', // The content of the notification
        'status',  // Optional: Status of the notification
    ];

    /**
     * Define the many-to-many relationship with the User model.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_notifications')
                    ->withPivot('is_read', 'created_at') // Include pivot table fields
                    ->withTimestamps(); // Automatically manage created_at and updated_at
    }
}

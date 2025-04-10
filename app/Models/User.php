<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Advertisement;
use App\Models\Inquiry;
use App\Models\Notification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'user_role',
        'ProfilePicture',
        'Contact',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Define the relationship with advertisements.
     */
    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class, 'seller_id', 'id'); // if seller is a user
    }

    /**
     * Define the relationship with wish list items.
     */
    public function wishListItems(): BelongsToMany
    {
        return $this->belongsToMany(Advertisement::class, 'wish_list_items', 'id');
    }

    /**
     * Define the relationship with inquiries.
     */
    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    /**
     * Define the relationship with notifications.
     */
    public function notifications(): BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'user_notifications')
                    ->withPivot('is_read', 'created_at') // Include pivot table fields
                    ->withTimestamps(); // Automatically manage created_at and updated_at
    }
}


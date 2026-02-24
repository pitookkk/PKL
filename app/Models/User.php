<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'role',
        'phone_number',
        'points',
        'membership_level',
        'total_spent',
    ];

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Get all reviews by the user.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the customer's hardware assets.
     */
    public function customerAssets()
    {
        return $this->hasMany(CustomerAsset::class);
    }

    /**
     * Get the customer's service tickets.
     */
    public function serviceTickets()
    {
        return $this->hasMany(ServiceTicket::class);
    }

    /**
     * Get the customer's loyalty points.
     */
    public function loyaltyPoint()
    {
        return $this->hasOne(LoyaltyPoint::class);
    }

    /**
     * Get all wishlist items for the user.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get all interaction logs for the user.
     */
    public function interactionLogs()
    {
        return $this->hasMany(InteractionLog::class);
    }

    public function communityBuilds()
    {
        return $this->hasMany(CommunityBuild::class);
    }

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
}

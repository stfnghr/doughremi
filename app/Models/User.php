<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Uncomment if you implement email verification
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Support\Facades\Hash; // No longer needed here if using 'hashed' cast

class User extends Authenticatable // Optional: implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin', // Ensure this is fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean', // Cast is_admin to boolean
        'password' => 'hashed',   // Automatically hashes passwords on set & works with create()
    ];

    /**
     * Define the relationship with Orders.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check if the user is an administrator.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin; // Explicitly cast to boolean
    }

    // REMOVE this mutator if you are using the 'hashed' cast for 'password'
    // public function setPasswordAttribute($password)
    // {
    //     $this->attributes['password'] = Hash::make($password);
    // }
}
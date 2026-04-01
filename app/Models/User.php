<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens; // Sanctum
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // ✅ Correct

    protected $fillable = [
        'name',
        'email',
        'password',
        'otp',               //  ADD THIS
        'otp_expires_at',    //  ADD THIS
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp', //  Optional (hide OTP from API response)
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime', //  IMPORTANT
            'password' => 'hashed',
        ];
    }
}
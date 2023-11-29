<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'description',
        'country',
        'state',
        'city',
        'address',
        'mobile_no',
        'profile_pic',
        'user_role',
        'age',
        'hourly_price',
        'mobile_otp'
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
        'password' => 'hashed',
    ];

    public function escortImages()
    {
        return $this->hasMany(ProfileImages::class, 'user_id', 'id')->where('type', '=', 'image');
    }

    public function escortVideos()
    {
        return $this->hasMany(ProfileImages::class, 'user_id', 'id')->where('type', '=', 'video');
    }
}

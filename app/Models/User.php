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
        'image_profile',
        'role',
        'email_verified_at',
        'verification_code',
        'instagran_profile',
        'facebook_page',
        'first_login',
        'phone',
        'password',
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

    public function partner()
    {
        return $this->hasOne(Partner::class);
    }

    public function seller()
    {
        return $this->hasOne(Seller::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function address()
    {
        $this->hasOne(Address::class);
    }
}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;


class ResetPassword extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    public $table = 'password_reset_tokens';

    protected $fillable = [
        'email',
        'token',
        'created_at',
        'updated_at',
    ];
    public function setPasswordAttribute($value)
    {
        // $this->attributes['password'] = Hash::make($value);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'password',
        // 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
        // 'password' => 'hashed',
    ];
}
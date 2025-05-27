<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    
    protected $fillable = [
        'role_id',
        'u_name',
        'u_email',
        'u_pass',
        'status' // Active, Pending
    ];

    protected $hidden = [
        'u_pass',
        'remember_token' 
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class, );
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, );
    }

        public function userStatus()
    {
        return $this->belongsTo(UserStatus::class, 'status_id');
    }

}
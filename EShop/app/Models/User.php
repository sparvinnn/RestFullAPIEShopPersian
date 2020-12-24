<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
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
     * @var array
     */
    protected $fillable = [
        'username',
        'f_name',
        'l_name',
        'mobile',
        'national_code',
        'mobile_verified_at',
        'county',
        'city',
        'address',
        'postal_code',
        'avatar',
        'email',
        'email_verified_at',
        'password',
        'role_id',
        'branch_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
    ];

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function county(){
        return $this->belongsTo(County::class);
    }

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }
}

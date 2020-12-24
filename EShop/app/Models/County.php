<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function cities(){
        return $this->hasMany(City::class);
    }

    public function users(){
        return $this->hasMany(User::class);
    }

    public function branches(){
        return $this->hasMany(Branch::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phones',
        'county',
        'city',
        'address',
        'postal_code',
        'fax'
    ];

    public function county(){
        return $this->belongsTo(County::class);
    }

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function users(){
        return $this->hasMany(User::class);
    }
}

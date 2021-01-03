<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public function product(){
        return $this->hasMany(Product::class);
    }
}

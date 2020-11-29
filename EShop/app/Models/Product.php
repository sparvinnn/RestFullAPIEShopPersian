<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'brand',
        'describe',
        'price',
        'image'
    ];


    public function category(){
        return $this->belongsTo('App/Models/Product');
    }
}

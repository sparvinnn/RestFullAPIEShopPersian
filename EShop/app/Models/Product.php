<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder\Property;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'images',
        'video',
        'description',
        'category_id',
        'branch_id',
        'count'
    ];

//    public function category(){
//        return $this->belongsTo(Category::class);
//    }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function properties(){
        return $this->hasMany(ProductProperty::class);
    }
}

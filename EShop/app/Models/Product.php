<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder\Property;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'images',
        'video',
        'description',
        'category_id',
        'branch_id',
        'count',
        'inventory_number',
        'total_number',
        'sales_number',
        'rate',
        'vote'
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
    public function media(){
        return $this->hasMany(Media::class);
    }
}

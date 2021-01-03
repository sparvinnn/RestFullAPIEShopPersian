<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'property_id',
        'value',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function property(){
        return $this->belongsTo(CategoryMeta::class);
    }

}

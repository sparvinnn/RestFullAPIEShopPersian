<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryMeta extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['key', 'value', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function product_property(){
        return $this->hasMany(ProductProperty::class);
    }
}

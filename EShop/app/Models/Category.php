<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name_fa', 'name_en', 'slug', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class,  'parent_id');
    }

    public function meta()
    {
        return $this->hasMany(CategoryMeta::class)
//            ->select('id', 'key', 'value')
            ;
    }

//    public function product(){
//        return $this->hasMany(Product::class);
//    }
}

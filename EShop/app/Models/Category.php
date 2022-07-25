<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name_fa', 
        'name_en', 
        'slug', 
        'parent_id',
        'category_id_giv',
        'category_code_giv',
        'parent_category_code_giv',
        'category_is_active_giv',
        'level_giv',
        'last_date_giv'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class,  'parent_id')->where('is_active', 1);
    }

    public function properties()
    {
        return $this->hasMany(CategoryProperty::class)
//            ->select('id', 'key', 'value')
            ;
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

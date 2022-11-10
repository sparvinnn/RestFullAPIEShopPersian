<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;


    protected $table = "categories";

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
        'last_date_giv'
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->where('is_active', 1);
    }

    public function field()
    {
        return $this->hasMany(CategoryField::class);
    }

    public function properties()
    {
        return $this->hasMany(CategoryProperty::class)
    // ->select('id', 'key', 'value')
    ;
    }
    public function meta()
    {
        return $this->hasMany(CategoryMeta::class)
    // ->select('id', 'key', 'value')
        ;
    }

    public function getParentsAttribute()
    {
        $parents = collect([]);

        $parent = $this->parent;

        while(!is_null($parent)) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents;
    }

    public function getParentsNames() {
        if($this->parent) {
            return $this->parent->getParentsNames(). " - " . $this->name_fa;
        } else {
            return $this->name;
        }
    }

// public function product(){
// return $this->hasMany(Product::class);

}

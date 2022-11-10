<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryField extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'field_id',
        'searchable'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }   
     
    public function field(){
        return $this->belongsTo(Field::class);
    }
}

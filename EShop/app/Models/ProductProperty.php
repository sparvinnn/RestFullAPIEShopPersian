<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductProperty extends Model
{
    use HasFactory;


    protected $fillable = [
        'product_id',
        'branch_id',
        'size',
        'material',
        'color',
        'design',
        'sleeve',
        'piece',
        'set_type',
        'description',
        'maintenance',
        'made_in',
        'origin',
        'type',
        'for_use',
        'collar',
        'height',
        'physical_feature',
        'production_time',
        'demension',
        'crotch',
        'close',
        'drop',
        'cumin',
        'close_shoes',
        'typeـofـclothing',
        'specialized_features',
        'sell_price'];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function property(){
        return $this->belongsTo(CategoryMeta::class);
    }

}

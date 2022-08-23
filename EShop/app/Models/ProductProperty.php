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
        'size_id',
        'material_id',
        'color_id',
        'design_id',
        'sleeve_id',
        'piece_id',
        'set_type_id',
        'maintenance_id',
        'made_in_id',
        'origin_id',
        'type_id',
        'for_use_id',
        'collar_id',
        'height_id',
        'physical_feature_id',
        'demension_id',
        'crotch_id',
        'close_id',
        'drop_id',
        'cumin_material_id',
        'close_shoe_id',
        'typeـofـclothing_id',
        'outerـpocket_id',
        'inner_pocket_id',
        'bag_handle_id',
        'shower_strap_id',
        'top_material_id',
        'heel_id',
        'bag_model_id',
        'is_active',
        'sell_price'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function property(){
        return $this->belongsTo(CategoryMeta::class);
    }

}

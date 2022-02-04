<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
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
        'specialized_features'];
    
}

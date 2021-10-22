<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable= [
        'user_id',
        'payed',
        'method',
        'amount',
        'creditCardNumber',
        'ref_id'
    ];

    public function buyer(){
        return $this->belongsTo(User::class);
    }

    public function products(){
//        return
    }
}

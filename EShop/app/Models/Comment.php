<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'title',
        'description',
        'suggestion',
        'parent_id',
        'admin_id'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent(){
        return $this->belongsTo(Comment::class);
    }

    public function admin(){
        return $this->belongsTo(User::class, 'admin_id');
    }
}

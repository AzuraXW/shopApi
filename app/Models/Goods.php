<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'category_id', 'description', 'price', 'stock', 'cover', 'pics', 'details', 'title'];
    protected $casts = [
        'pics' => 'array'
    ];

    public function category () {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user () {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comment () {
        return $this->hasMany(Comment::class, 'goods_id', 'id');
    }
}

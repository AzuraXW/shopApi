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

    protected $appends = ['cover_url'];
    public function getCoverUrlAttribute () {
        return oss_url($this->cover);
    }

    public function getPicsUrlAttribute () {
        // 使用集合处理每一项元素
        return collect($this->pics)->map(function ($item) {
           return oss_url($item);
        });
    }

    public function category () {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user () {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments () {
        return $this->hasMany(Comments::class, 'goods_id', 'id');
    }
}

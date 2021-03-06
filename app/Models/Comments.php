<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $guarded = [];

    use HasFactory;
    protected $casts = [
        'pics' => 'array'
    ];

    public function goods () {
        return $this->belongsTo(Goods::class, 'goods_id', 'id');
    }

    public function user () {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

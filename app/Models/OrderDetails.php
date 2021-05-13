<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'goods_id', 'price', 'num'];

    public function goods () {
        return $this->belongsTo(Goods::class, 'goods_id', 'id');
    }
}

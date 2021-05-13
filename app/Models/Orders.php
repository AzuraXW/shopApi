<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'order_no', 'amount', 'address_id'];

    public function user () {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function orderDetails () {
        return $this->hasMany(OrderDetails::class, 'order_id', 'id');
    }

    public function goods () {
        return $this->hasManyThrough(
            Goods::class,  // 最终关联的模型
            OrderDetails::class,  // 中间模型
            'order_id',   // 中间模型和本模型关联的键
            'id',  // 最终关联模型的id
            'id',  // 本模型与中间模型关联的键
            'goods_id'   // 用户表本地键
        );
    }
}

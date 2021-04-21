<?php

namespace App\Transformers;


use App\Models\OrderDetails;
use League\Fractal\TransformerAbstract;

class OrderDetailsTransformer extends TransformerAbstract {
    protected $availableIncludes = ['goods'];
    public function transform(OrderDetails $orderDetails) {
        return [
            'id' => $orderDetails->id,
            'order_id' => $orderDetails->order_id,
            'goods_id' => $orderDetails->goods_id,
            'price' => $orderDetails->price,
            'num' => $orderDetails->num
        ];
    }

//    public function includeUser (User $user) {
//        return $this->response->item($user, new UserTransformer());
//    }
//
    public function includeGoods (OrderDetails $orderDetails) {
        return $this->item($orderDetails->goods, new GoodsTransformer());
    }
}

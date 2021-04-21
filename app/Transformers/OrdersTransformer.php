<?php

namespace App\Transformers;


use App\Models\OrderDetails;
use App\Models\Orders;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class OrdersTransformer extends TransformerAbstract {
    public $availableIncludes = ['user', 'orderDetails'];
    public function transform(Orders $orders) {
        return [
            'id' => $orders->id,
            'user_id' => $orders->user_id,
            'order_no' => $orders->order_no,
            'amount' => $orders->amount,
            'status' => $orders->status,
            'address_id' => $orders->address_id,
            'express_type' => $orders->express_type,
            'express_no' => $orders->express_no,
            'pay_time' => $orders->pay_time,
            'pay_type' => $orders->pay_type,
            'trade_no' => $orders->trade_no
        ];
    }

    public function includeUser (Orders $orders) {
        return $this->item($orders->user, new UserTransformer());
    }

    public function includeorderDetails (Orders $orders) {
        return $this->collection($orders->orderDetails, new OrderDetailsTransformer());
    }
}

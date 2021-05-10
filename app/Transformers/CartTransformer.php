<?php

namespace App\Transformers;

use App\Models\Cart;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract {
    public function transform(Cart $cart) {
        return [
            'id' => $cart->id,
            'goods' => $cart->goods()->select('id', 'title', 'cover', 'price', 'is_on', 'stock')->first(),
            'num' => $cart->num
        ];
    }
}

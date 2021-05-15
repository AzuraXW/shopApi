<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Cart;
use App\Models\Goods;
use App\Models\Orders;
use App\Transformers\OrdersTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{
    // 购物车预览Api
    public function preview () {
        // TODO 先写个假地址列表
        $address = [
            [
                'name' => 'Tom',
                'address' => '广东省东莞市XXXX',
                'phone' => '1345679'
            ]
        ];
        $user = auth('api')->user();
        $orders = Cart::where('user_id', $user->id)
            ->where('is_checked', 1)
            ->with('goods:id,title,cover')
            ->get();

        return $this->response->array([
            'success' => true,
            'message' => '数据获取成功',
            'address' => $address,
            'orders' => $orders
        ]);
    }

    // 用户创建订单
    public function store (Request $request) {
        $address_id = $request->input('address_id');
        // TODO 这里还需要验证地址是否存在，需要等到有地址表之后再说
        if ($address_id == '') {
            return $this->response->array([
                'success' => false,
                'message' => '地址不能为空'
            ])->setStatusCode(422);
        }
        $user = auth('api')->user();
        // 随机生成订单编号
        $order_no = date('YmdHis') . rand(1000000000000, 2000000000000);

        // 订单总金额
        $amount = 0;
        $insertOrderDetailsData = [];
        $cartQuery = Cart::where('user_id', $user->id)
            ->where('is_checked', 1)
            ->with('goods:id,title,price,stock');

        $carts = $cartQuery->get();
        // 判断用户的购物车中是否选中的商品
        if (count($carts) === 0) {
            return $this->response->array([
                'success' => false,
                'message' => '购物车中无选中商品'
            ])->setStatusCode(400);
        }

        // 存放无法购买的商品
        $not_goods = [];
        foreach ($carts as $key => $cart) {
            // 判断购买数量是否超出库存
            if ($cart->goods->stock < $cart->num) {
                array_push($not_goods, $cart->goods);
            }
            // 添加需要插入OrderDetail表中的数据
            array_push($insertOrderDetailsData, [
                'goods_id' => $cart->goods->id,
                'price' => $cart->goods->price,
                'num' => $cart->num
            ]);
            $amount += $cart->num * $cart->goods->price;
        }

        // 如果存在无法购买的商品，库存不足
        if (count($not_goods) > 0) {
            return $this->response->array([
                'success' => false,
                'message' => '商品库存不足',
                'goods' => $not_goods
            ])->statusCode(400);
        }

        try {
            DB::beginTransaction();
            // 创建订单详情
            $order = Orders::create([
                'user_id' => $user->id,
                'order_no' => $order_no,
                'amount' => $amount,
                'address_id' => $address_id
            ]);
            // 减去对应的库存
            foreach ($carts as $cart) {
                Goods::where('id', $cart->goods_id)->decrement('stock', $cart->num);
            }
            // 创建订单详情
            $order->orderDetails()->createMany($insertOrderDetailsData);
            // 删除已经生成订单的购物车记录
            $cartQuery->delete();
            DB::commit();
            return $this->response->array([
                'success' => true,
                'message' => '成功提交订单'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function show (Orders $orders) {
        return $this->response->item($orders, new OrdersTransformer());
    }
}

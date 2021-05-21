<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Cart;
use App\Models\Goods;
use App\Models\Orders;
use App\Models\Address;
use App\Transformers\OrdersTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Facades\Express\Facade\Express;

class OrderController extends BaseController
{
    // 订单列表
    public function index (Request $request) {
        $status = $request->input('status');
        $title = $request->input('title');

        $orders = Orders::where('user_id', auth('api')->id())
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($title, function ($query) use ($title) {
                return $query->whereHas('goods', function ($query) use ($title) {
                    return $query->where('title', 'like', "%{$title}%");
                });
            })
            ->paginate(5);
        return $this->response->paginator($orders, new OrdersTransformer);
    }

    // 购物车预览Api
    public function preview () {
        $user = auth('api')->user();
        // 用户地址信息
        $addresses = Address::where('user_id', $user->id)->get();

        $orders = Cart::where('user_id', $user->id)
            ->where('is_checked', 1)
            ->with('goods:id,title,cover')
            ->get();

        return $this->response->array([
            'success' => true,
            'message' => '数据获取成功',
            'addresses' => $addresses,
            'orders' => $orders
        ]);
    }

    // 用户创建订单
    public function store (Request $request) {
        $user = auth('api')->user();
        $address_id = $request->input('address_id');
        // 验证地址是否正确
        $address = Address::find($address_id);
        if (empty($address)) {
            return $this->response->array([
                'success' => false,
                'message' => '地址不存在'
            ])->setStatusCode(422);
        }
        if ($address->user_id != $user->id) {
            return $this->response->array([
                'success' => false,
                'message' => '地址错误，请重新选择'
            ])->setStatusCode(400);
        }

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

    // 订单详情
    public function show (Orders $orders) {
        return $this->response->item($orders, new OrdersTransformer());
    }

    // 快递查询
    public function express (Orders $orders) {
        if (!in_array($orders->status, [3, 4])) {
            return $this->response->array([
                'success' => false,
                'message' => '订单异常'
            ])->setStatusCode(400);
        }

        $track = Express::track($orders->express_type, $orders->express_no);
        if ($track['Success'] == false) {
            return $this->response->array([
                'success' => false,
                'message' => $track['Message']
            ])->setStatusCode(500);
        }

        return $this->response->array($track);
    }

    // 确认收货
    public function confirm (Orders $orders) {
        if ($orders->status != 3) {
            return $this->response->array([
                'success' => false,
                'message' => '订单异常'
            ]);
        }
        try {
            DB::beginTransaction();
            $orders->status = 4;
            $orders->save();

            $ordersDetail = $orders->orderDetails;
            foreach ($ordersDetail as $detail) {
                Goods::where('id', $detail->goods_id)->increment('sales', $detail->num);
            }

            DB::commit();
            return $this->response->array([
                'success' => true,
                'message' => '成功收货'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}

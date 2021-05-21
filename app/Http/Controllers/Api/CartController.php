<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Cart;
use App\Models\Goods;
use App\Transformers\CartTransformer;
use Illuminate\Http\Request;

class CartController extends BaseController
{
    /**
     * 购物车列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('api')->user();
        $carts = Cart::where('user_id', $user->id)->get();

        return $this->response->collection($carts, new CartTransformer())
            ->setMeta([
                'success' => true,
                'message' => '获取用户购物车成功'
            ]);
    }

    /**
     * 添加商品至购物车
     */
    public function store(Request $request)
    {
        $goods_id = $request->input('goods_id');
        $is_exists = Goods::where('id', $goods_id)->exists();
        if (!$is_exists) {
            return $this->response->array([
                'success' => false,
                'message' => '该商品不存在'
            ])->setStatusCode(400);
        }
        // 当前用户信息
        $user = auth('api')->user();
        $user_carts = Cart::where('user_id', $user->id)->get();
        foreach ($user_carts as $item) {
            // 判断当前用户是否已经有该商品，如果有就将数量加1
            if ($item['goods_id'] === $goods_id) {
                $num = $item['num'] + 1;
                Cart::where('id', $item['id'])
                ->update([
                   'num' => $num
                ]);
                return $this->response->array([
                    'success' => true,
                    'message' => '已添加至购物车'
                ]);
            }
        }

        // 当用户购物车中没有该商品就创建购物车记录
        Cart::create([
            'user_id' => $user->id,
            'goods_id' => $goods_id
        ]);

        return $this->response->array([
            'success' => true,
            'message' => '添加购物车成功'
        ]);
    }

    /**
     * 更新购物车商品数据
     */
    public function update(Request $request, Cart $cart)
    {
        // TODO 商品参数更新
        $num = intval($request->input('num'));
        if ($num <= 0 || $num > 999) {
            return $this->response->array([
                'success' => false,
                'message' => '商品数量最少不少于0，最大不超过999'
            ])->setStatusCode(422);
        }

        $cart->num = $num;
        $cart->save();
        return $this->response->array([
            'success' => true,
            'message' => '更新成功'
        ]);
    }

    /**
     * 删除购物车中的商品
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();
        return $this->response->array([
            'success' => true,
            'message' => '删除成功'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Goods;
use Illuminate\Http\Request;

class GoodsController extends BaseController
{
    public function show ($id) {
        // 商品详情
        $goods = Goods::where('id', $id)
            ->with([
                'comments.user' => function($query) {
                    $query->select('id', 'name', 'avatar');
                }
            ])
            ->first()
            ->append('pics_url');
        // 相似商品
        $resemblance_goods = Goods::where('category_id', $goods->category_id)
            ->select('id', 'title', 'price', 'cover', 'sales')
            ->inRandomOrder()
            ->take(10)
            ->get();
        // 返回数据
        return $this->response->array([
           'goods' => $goods,
            'resemblance_goods' => $resemblance_goods
        ]);
    }

    public function list (Request $request) {
        $cateogry_id = $request->input('category_id');
        $title = $request->input('title');
        $order_price = $request->input('order_price');
        $order_sales = $request->input('order_sales');
        $order_comments_count = $request->input('order_comments_count');
        // 商品列表
        // 排序
        // 相似商品
        $goods = Goods::where('is_on', 1)
        ->when($cateogry_id, function ($query) use ($cateogry_id) {
            return $query->where('category_id', $cateogry_id);
        })
        ->when($title, function ($query) use ($title) {
            return $query->where('title', 'like', "%$title%");
        })
        ->when($order_price == 1, function ($query) use ($order_price) {
            return $query->orderBy('price', 'desc');
        })
        ->when($order_sales == 1, function ($query) use ($order_sales) {
            return $query->orderBy('sales', 'desc');
        })
        ->withCount('comments')
        ->when($order_comments_count == 1, function ($query) use ($order_comments_count) {
            return $query->orderBy('comments_count', 'desc');
        })
        ->Paginate(20)
        ->appends([
            'title' => $title,
            'category_id' => $cateogry_id
        ]);

        return $this->response->array($goods->toArray());
    }
}

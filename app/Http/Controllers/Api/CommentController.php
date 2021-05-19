<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Comments;
use App\Models\Orders;
use Illuminate\Http\Request;
use PhpParser\Comment;

class CommentController extends BaseController
{
    // 用户评论（添加评论）
    public function store (Request $request, Orders $orders) {
        $goods_id = $request->input('goods_id');
        $content = $request->input('content');
        if (!$goods_id || !$content) {
            return $this->response->array([
                'success' => false,
                'message' => '缺少必要参数'
            ])->setStatusCode(422);
        }

        // 只有状态为4的订单才可以评论
        if ($orders->status != 4) {
            return $this->response->array([
                'success' => false,
                'message' => '订单状态异常'
            ])->setStatusCode(400);
        }

        // 判断需要评论的商品是否是属于该订单的
        if (!collect($orders->orderDetails()->pluck('goods_id'))->contains($goods_id)) {
            return $this->response->array([
                'success' => false,
                'message' => '商品与订单不符'
            ])->setStatusCode(400);
        }

        $user_id = auth('api')->id();
        // 不能重复评论
        $isExist = Comments::where('user_id', $user_id)
            ->where('goods_id', $goods_id)
            ->where('order_id', $orders->id)
            ->count();
        if ($isExist) {
            return $this->response->array([
                'success' => false,
                'message' => '该商品已经评论过了'
            ])->setStatusCode(400);
        }

        $request->offsetSet('user_id', $user_id);
        $request->offsetSet('order_id', $orders->id);
        Comments::create($request->except([
            'reply',
            'id'
        ]));
        return $this->response->array([
            'success' => true,
            'message' => '评论成功'
        ]);
    }
}

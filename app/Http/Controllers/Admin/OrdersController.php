<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Orders;
use App\Transformers\OrdersTransformer;
use Illuminate\Http\Request;

class OrdersController extends BaseController
{
    // 订单列表
    public function index (Request $request) {
        // 订单编号
        $order_no = $request->input('order_no');
        // 支付单号
        $trade_no = $request->input('trade_no');
        // 订单状态
        $status = $request->input('status');
        $limit = $request->input('limit');

        $orders = Orders::when($order_no, function ($query) use ($order_no) {
            return $query->where('order_no', $order_no);
        })->when($trade_no, function ($query) use ($trade_no) {
            return $query->where('trade_no', $trade_no);
        })->when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        })->paginate($limit);

        return $this->response->paginator($orders, new OrdersTransformer())->setMeta([
            'success' => true,
            'message' => '获取成功'
        ]);
    }

    // 订单详情
    public function show (Orders $orders) {
        return $this->response->item($orders, new OrdersTransformer())->setMeta([
            'success' => true,
            'message' => '获取成功'
        ]);
    }

    // 发货
    public function post (Request $request, Orders $orders) {
        $express_type = $request->input('express_type');
        $express_no = $request->input('express_no');
        if (!in_array($express_type, ['SF', 'YT', 'YD'])) {
            return $this->response->array([
                'success' => false,
                'message' => '没有该快递类型'
            ])->setStatusCode(422);
        }
        if ($express_no == '') {
            return $this->response->array([
                'success' => false,
                'message' => '快递单号不能为空'
            ])->setStatusCode(422);
        }

        $orders->express_type = $express_type;
        $orders->express_no = $express_no;
        $orders->status = 3;
        $orders->save();
        return $this->response->array([
            'success' => true,
            'message' => '发货成功'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Orders;
use Illuminate\Http\Request;
use Yansongda\Pay\Log;
use Yansongda\Pay\Pay;

class PayController extends BaseController
{
    // 订单支付
    public function pay (Request $request, Orders $orders) {
        $type = $request->input('type');
        if ($type === '') {
            return $this->response->array([
                'success' => false,
                'message' => '订单类型不能为空'
            ])->setStatusCode(422);
        }
        if (!in_array($type, ['aliyun', 'wechat'])) {
            return $this->response->array([
                'success' => false,
                'message' => '支付类型只能是aliyun或者wechat'
            ])->setStatusCode(422);
        }
        if ($orders->status != 1) {
            return $this->response->array([
                'success' => false,
                'message' => '订单状态异常无法支付'
            ])->setStatusCode(400);
        }
        // 选择aliyun支付
        if ($type === 'aliyun') {
            $order = [
                'out_trade_no' => $orders->order_no,
                'total_amount' => $orders->amount,
                'subject' => $orders->goods()->first()->title . '等' . $orders->goods()->count() . '件商品'
            ];

            return Pay::alipay(config('pay')['alipay'])->scan($order);
        }
    }

    public function notifyAliyun () {
        $alipay = Pay::alipay(config('pay')['alipay']);

        try{
            $data = $alipay->verify(); // 是的，验签就这么简单！
            // 请自行对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
            // 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）；
            // 4、验证app_id是否为该商户本身。
            // 5、其它业务逻辑情况
            if ($data->trade_status === 'TRADE_SUCCESS' || $data->trade_status === 'TRADE_FINISHED') {
                $order = Orders::where('order_no', $data->out_trade_no)->first();

                $order->update([
                    'status' => 2,
                    'pay_time' => $data->gmt_payment,
                    'pay_type' => '支付宝',
                    'trade_no' => $data->trade_no
                ]);
            }
            Log::info($data);
            Log::debug('Alipay notify', $data->all());
        } catch (\Exception $e) {
            // $e->getMessage();
        }

        return $alipay->success()->send();// laravel 框架中请直接 `return $alipay->success()`
    }
}

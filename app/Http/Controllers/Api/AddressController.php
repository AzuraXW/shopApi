<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\AddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends BaseController
{
    /**
     * 地址列表
     */
    public function index()
    {
        $user_id = auth('api')->id();
        $addresses = Address::where('user_id', $user_id)->get();
        return $addresses;
    }

    /**
     * 添加收货地址
     */
    public function store(AddressRequest $request)
    {
        $request->offsetSet('user_id', auth('api')->id());
        Address::create($request->all());
        return $this->response->array([
            'success' => true,
            'message' => '地址添加成功'
        ]);
    }

    /**
     * 收货地址详情
     */
    public function show(Address $address)
    {
        $this->validBelongs($address->user_id);

        return $this->response->array([
            'success' => true,
            'message' => '获取成功',
            'data' => $address
        ]);
    }

    /**
     * 更新收货地址
     */
    public function update(AddressRequest $request, Address $address)
    {
        $this->validBelongs($address->user_id);
        $address->update($request->except(['user_id', 'is_default']));
        return $this->response->array([
            'success' => true,
            'message' => '更新成功'
        ]);
    }

    /**
     * 删除收货地址
     */
    public function destroy(Address $address)
    {
        $this->validBelongs($address->user_id);

        $address->delete();
        return $this->response->array([
            'success' => true,
            'message' => '地址删除成功'
        ]);
    }

    /**
     * 将收货地址设置为默认
     */
    public function default (Address $address) {
        $user_id = auth('api')->id();
        // 地址是否属于该用户
        $this->validBelongs($address->user_id);
        // 先将默认的地址设置为0
        Address::where('user_id', $user_id)
            ->where('is_default', 1)
            ->update([
                'is_default' => 0
            ]);
        // 将用户指定的地址设置为默认
        $address->is_default = 1;
        $address->save();

        return $this->response->array([
            'success' => true,
            'message' => '默认地址设置成功'
        ]);
    }

    private function validBelongs ($user_id) {
        if ($user_id != auth('api')->id()) {
            return $this->response->array([
                'success' => false,
                'message' => '该地址不属于该用户'
            ])->setStatusCode(400);
        }
    }
}

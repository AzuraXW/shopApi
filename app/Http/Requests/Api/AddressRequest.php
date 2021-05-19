<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use App\Models\Chain;

class AddressRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'city_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $city = Chain::find($value);
                    if (empty($city)) {
                        $fail('区域地址不存在');
                        return;
                    }
                    if ($city->level < 3) {
                        $fail('区域字段必须是市级以下');
                    }
                }
            ],
            'address' => 'required',
            'phone' => 'required|regex:/^1[3456789]{1}\d{9}$/',
        ];
    }

    public function messages () {
        return [
            'name.required' => '收货人姓名不能为空',
            'city_id.required' => '城市不能为空',
            'address.required' => '详细地址不能为空',
            'phone.required' => '手机号不能为空',
            'phone.regex' => '手机号不符合规范',
        ];
    }
}

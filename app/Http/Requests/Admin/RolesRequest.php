<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class RolesRequest extends BaseRequest
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
            'cn_name' => 'required'
        ];
    }

    public function messages () {
        return [
            'name.required' => '角色的英文名称不能为空',
            'cn_name.required' => '角色的中文名称不能为空'
        ];
    }
}

<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class SlidesRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'img' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '图片标题不能为空',
            'img.required' => '图片名称不能为空'
        ];
    }
}

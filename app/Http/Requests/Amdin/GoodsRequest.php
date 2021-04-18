<?php

namespace App\Http\Requests\Amdin;

use App\Http\Requests\BaseRequest;

class GoodsRequest extends BaseRequest
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
            'category_id' => 'required',
            'description' => 'required',
            'price' => 'required|min:0',
            'stock' => 'required|min:0',
            'cover' => 'required',
            'pics' => 'required|array',
            'details' => 'required'
        ];
    }

    public function messages () {
        return [
            'title.required' => '标题不能为空',
            'category_id.required' => '分类id不能为空',
            'description.required' => '描述不能为空',
            'price.required' => '商品价格不能为空',
            'price.min' => '价格不能为负数',
            'stock.min' => '库存不能为负数',
            'stock.required' => '库存不能为空',
            'cover.required' => '封面图片不能为空',
            'pics.required' => '图片集合不能为空',
            'pics.array' => '图片集合必须是数组',
            'details.required' => '详情不能为空'
        ];
    }
}

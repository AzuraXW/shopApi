<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\SpecGroup;
use App\Models\SpecParam;
use Illuminate\Http\Request;

class SpecController extends BaseController
{
    // 商品规格组，商品规格组的键相关操作
    // 主要涉及表为spec_group spec_param

    // 添加商品规格组
    public function addSpecGroup (Request $request) {
        $category_id = $request->input('category_id');
        $name = $request->input('name');
        // 严谨来说应该验证一下分类id是否在分类表中存在，节约时间
        if ($category_id == '' || $name == '') {
            return $this->response->array([
                'success' => false,
                'message' => '缺少必要参数'
            ])->setStatusCode(403);
        }
        SpecGroup::create([
            'category_id' => $category_id,
            'name' => $name
        ]);
        return $this->response->array([
            'success' => true,
            'message' => '成功添加商品规格组'
        ]);
    }

    // 删除商品规格组
    public function deleteSpecGroup (SpecGroup $specGroup) {
        $specGroup->delete();
        return $this->response->array([
            'success' => true,
            'message' => '成功删除商品规格组'
        ]);
    }

    // 更改商品规格组, 只能更新名字，不能更改分类id
    public function updateSpecGorup (Request $request, SpecGroup $specGroup) {
        $name = $request->input('name');
        if ($name == '') {
            return $this->response->array([
                'success' => false,
                'message' => '商品规格组名字不能为空'
            ])->setStatusCode(403);
        }
        $specGroup->update([
            'name' => $name
        ]);

        return $this->response->array([
            'success' => true,
            'message' => '更新成功'
        ]);
    }

    // 添加规格参数
    public function addSpecParams (Request $request) {
        $params = $request->only([
            'category_id',
            'group_id',
            'name',
            'numeric',
            'unit',
            'searching',
            'segments',
            'generic'
        ]);
        try {
            SpecParam::create($params);
        } catch (Exception $e) {
            return $this->response->array([
                'success' => false,
                'message' => '添加失败'
            ])->setStatusCode('403');
        }
        return $this->response->array([
            'success' => true,
            'message' => '成功添加规格参数'
        ]);
    }

    // 更新规格参数
    public function updateSpecParam (Request $request, SpecParam $specParam) {
        $params = $request->only([
            'name',
            'numeric',
            'unit',
            'searching',
            'segments',
            'generic'
        ]);
        $specParam->update($params);
        return $this->response->array([
            'success' => true,
            'message' => '成功更新规格参数'
        ]);
    }

    // 删除规格参数
    public function deleteSpecParam (SpecParam $specParam) {
        $specParam->delete();
        return $this->response->array([
            'success' => true,
            'message' => '成功删除规格参数'
        ]);
    }
}

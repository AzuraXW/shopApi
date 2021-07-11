<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\SpecParam;
use App\Models\Spu;
use Illuminate\Http\Request;

class SpuController extends BaseController
{
    // 商品集列表
    public function spuList () {
        $list = Spu::with('detail')->get();
        foreach ($list as $row) {
            // 将数据库中的规格参数值从json转换成array
            $special_spec = json_decode($row->detail->special_spec, true);
            $generic_spec = json_decode($row->detail->generic_spec, true);
            // 存储规格json中的id换成name之后的结果
            $associate_special_spec = [];
            $associate_generic_spec = [];
            // 将id转换成name
            foreach (array_keys($special_spec) as $key) {
                $spec = SpecParam::find((int)$key);
                if ($spec['unit'] != '') {
                    $param_name = "{$spec['name']}({$spec['unit']})";
                } else {
                    $param_name = $spec['name'];
                }
                $associate_special_spec[$param_name] = $special_spec[$key];
            }
            foreach (array_keys($generic_spec) as $key) {
                $spec = SpecParam::find((int)$key);
                if ($spec['unit'] != '') {
                    $param_name = "{$spec['name']}({$spec['unit']})";
                } else {
                    $param_name = $spec['name'];
                }
                $associate_generic_spec[$param_name] = $generic_spec[$key];
            }
            // 以新的array替换旧的json
            $row->detail->special_spec = $associate_special_spec;
            $row->detail->generic_spec = $associate_generic_spec;
        }
        return $list;
    }
    // 添加商品集
    public function addSpu (Request $request) {
        $spu_params = $request->only([
            'name',
            'sub_title',
            'cid',
            'brand_id',
        ]);
        $spu_detail_params = $request->only([
            'description',
            'generic_spec',
            'special_spec',
            'packing_list',
            'after_service'
        ]);
        Spu::create($spu_params)->detail()->create($spu_detail_params);
        return $this->response->array([
            'success' => true,
            'message' => '添加成功'
        ]);
    }
}

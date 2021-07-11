<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Sku;
use App\Models\SpecParam;
use Illuminate\Http\Request;

class SkuController extends BaseController
{
    // 查询sku
    public function querySku (Request $request) {
        // 通过spu_id查询一个商品集下的具体的商品
        $spu_id = $request->input('spu_id');
        $result = Sku::where('spu_id', $spu_id)
            ->get();
        foreach ($result as $row) {
            // 将数据库中的规格参数值从json转换成array
            $own_spec = json_decode($row->own_spec, true);
            // 存储规格json中的id换成name之后的结果
            $associate_own_spec = [];
            // 将id转换成name
            foreach (array_keys($own_spec) as $key) {
                $spec = SpecParam::find((int)$key);
                if ($spec['unit'] != '') {
                    $param_name = "{$spec['name']}({$spec['unit']})";
                } else {
                    $param_name = $spec['name'];
                }
                $associate_own_spec[$param_name] = $own_spec[$key];
            }
            $row->price = $row->price / 100;
            // 以新的array替换旧的json
            $row->own_spec = $associate_own_spec;
        }
        return $result;
    }
    // 添加具体商品
    public function addSku (Request $request) {
        $params = $request->only([
            'spu_id',
            'title',
            'images',
            'stock',
            'price',
            'indexes',
            'own_spec'
        ]);
        Sku::create($params);
        return $this->response->array([
            'success' => true,
            'message' => '添加成功'
        ]);
    }
}

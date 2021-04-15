<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = cache_category_all();
        return $this->response->array([
            'status_code' => 200,
            'msg' => '成功获取分类列表',
            'data' => $category
        ]);
    }

    /**
     * 添加分类
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        $pid = $request->input('pid', 0);
        // 名称不能为空
        if (!$name) {
            return $this->response->array([
                'status_code' => 422,
                'msg' => '分类名称不能为空'
            ])->setStatusCode(422);
        }
        $level = $pid == 0 ? 1 : (Category::find($pid)->level + 1);
        if ($level > 3) {
            return $this->response->array([
                'status_code' => 422,
                'msg' => '最多三级分类'
            ])->setStatusCode(422);
        }
        $insertData = [
            'name' => $name,
            'pid' => $pid,
            'level' => $level
        ];
        Category::create($insertData);
        forget_cache_category();
        return $this->response->array([
            'status_code' => 200,
            'msg' => '添加成功'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

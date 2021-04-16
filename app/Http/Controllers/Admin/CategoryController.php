<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    /**
     * 获取分类列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->query('type');
        if ($type === 'all') {
            $category = cache_category_all();
        } else {
            $category = cache_category();
        }

        return $this->response->array([
            'success' => true,
            'message' => '成功获取分类列表',
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
        $insertData = $this->checkInput($request);
        // 同一层级下分类名称不能相同
        $equally = Category::where('pid', $insertData['pid'])->where('name', $insertData['name'])->get();
        if (!is_array($insertData)) return $insertData;
        if (count($equally->toArray()) > 0) {
            return $this->response->array([
                'success' => 422,
                'message' => '同一分类层级下已经存在该分类'
            ])->setStatusCode(422);
        }

        Category::create($insertData);
        return $this->response->array([
            'success' => true,
            'message' => '添加成功'
        ]);
    }

    /**
     * 单个分类详情
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $category;
    }

    /**
     * 编辑分类
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $updateData = $this->checkInput($request);
        if (!is_array($updateData)) return $updateData;
        $category->update($updateData);

        return $this->response->array([
            'success' => true,
            'message' => '更新成功'
        ]);
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

    // 改变分类状态
    public function status (Category $category) {
        $category->status = $category->status == 0 ? 1 : 0;
        $category->save();
        return $this->response->array([
            'success' => true,
            'message' => "分类状态成功改变",
            'status' => $category->status
        ]);
    }

    // 检查输入参数
    private function checkInput ($request) {
        $name = $request->input('name');
        $pid = $request->input('pid', 0);
        // 名称不能为空
        if (!$name) {
            return $this->response->array([
                'success' => false,
                'message' => '分类名称不能为空'
            ])->setStatusCode(422);
        }
        $level = $pid == 0 ? 1 : (Category::find($pid)->level + 1);
        // 不能超过三级分类
        if ($level > 3) {
            return $this->response->array([
                'success' => false,
                'message' => '最多三级分类'
            ])->setStatusCode(422);
        }

        return [
            'name' => $name,
            'pid' => $pid,
            'level' => $level
        ];
    }
}

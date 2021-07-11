<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Inline\Element\Strong;

class BrandController extends BaseController
{
    // 品牌列表
    public function brandList () {
        return collect(Brand::select('id', 'name', 'image')->get())->map(function ($row) {
            $row['image'] = asset($row['image']);
            return $row;
        });
    }

    // 添加品牌
    public function addBrand (Request $request) {
        $name = $request->input('name');
        $letter = strtoupper($request->input('letter'));
        $image = $request->file('image');
        $category_ids = $request->input('category_ids', '');  // 可选参数
        if ($image->isValid()) {
            // 上传成功,保存
            $path = $image->store('brand');
        }
        $brand = Brand::create([
            'name' => $name,
            'letter' => $letter,
            'image' => $path
        ]);
        if ($category_ids !== '') {
            // 创建时可以分配品牌所属的分类
            $categoryBrands = [];
            $category_ids = explode(',', $category_ids);
            foreach ($category_ids as $id) {
                array_push($categoryBrands, [
                    'category_id' => $id
                ]);
            }
            $brand->CategoryBrand()->createMany($categoryBrands);
        }
        return $this->response->array([
            'success' => true,
            'message' => '成功添加品牌'
        ]);
    }

    // 删除品牌
    public function deleteBrand (Request $request, Brand $brand) {
        /**
         删除品牌逻辑：只有当待删除的品牌没有被任何的分类引用时才可以删除
         */
        $is_reference = $brand->CategoryBrand()->count() > 0;
        if ($is_reference) {
            return $this->response->array([
                'success' => false,
                'message' => '该品牌被分类引用，无法删除'
            ])->setStatusCode(400);
        }
        $brand->delete();
        return $this->response->array([
            'success' => true,
            'message' => '删除成功'
        ]);
    }

    // 更新品牌
    public function updateBrand (Request $request, Brand $brand) {
        $name = $request->input('name');
        $letter = strtoupper($request->input('letter'));
        $image = $request->file('image');
        $path = '';
        if ($image && $image->isValid()) {
            Storage::delete($brand->image);
            $path = $image->store('brand');
        }
        $brand->update([
            'name' => $name,
            'letter' => $letter,
            'image' => $path ? $path : $brand->image
        ]);
        return $this->response->array([
            'success' => true,
            'message' => '更新成功'
        ]);
    }
}

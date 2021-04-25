<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GoodsRequest;
use App\Models\Category;
use App\Models\Goods;
use Illuminate\Http\Request;
use App\Transformers\GoodsTransformer;

class GoodsController extends BaseController
{
    /**
     * 商品列表
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $limit = $request->input('limit');
        $paginate = Goods::when($title, function ($query) use($title) {
            return $query->where('title', 'like', "%$title%");
        })->paginate($limit);
//        return $this->response->array([
//            'success' => true,
//            'message' => '商品列表获取成功',
//            'data' => $paginate['data'],
//            'total' => $paginate['total'],
//            'current_page' => $paginate['current_page'],
//            'last_page' => $paginate['last_page']
//        ]);
        return $response = $this->response->paginator($paginate, new GoodsTransformer())->setMeta([
            'success' => true,
            'message' => '获取商品列表成功'
        ]);
    }

    /**
     * 添加商品
     */
    public function store(GoodsRequest $request)
    {
        // 对分类进行检查，分类不能被禁用，必须是三级分类才能添加商品
        $category = Category::find($request->input('category_id'));
        if ($category->status === 0) {
            return $this->response->array([
                'success' => false,
                'message' => '该分类已经被禁用了'
            ])->setStatusCode(422);
        }
        if ($category->level !== 3) {
            return $this->response->array([
                'success' => false,
                'message' => '该分类不是三级分类'
            ])->setStatusCode(422);
        }
        $userId = auth('api')->id();
        $request->offsetSet('user_id', $userId);
        $good = Goods::create($request->all());
        if ($good) {
            return $this->response->array([
                'success' => true,
                'message' => '商品创建成功'
            ]);
        }
    }

    /**
     * 商品详情
     */
    public function show(Goods $good)
    {
        return $this->response->item($good, new GoodsTransformer())->setMeta([
            'success' => true,
            'message' => '成功获取商品详情'
        ]);
    }

    /**
     * 更新商品
     */
    public function update(Request $request, Goods $good)
    {
        if (count($request->all()) === 0) {
            return $this->response->array([
                'success' => false,
                'message' => '缺少需要更新的参数'
            ])->setStatusCode(422);
        }
        $good->update($request->all());
        $good->save();
        return $this->response->array([
            'success' => true,
            'message' => '更新成功'
        ]);
    }

    public function is_on (Goods $good) {
        $is_on = $good->is_on === 0 ? 1 : 0;
        $good->is_on = $is_on;
        $good->save();
        return $this->response->array([
            'success' => true,
            'message' => $is_on === 0 ? '下架成功' : '上架成功',
            'is_on' => $is_on
        ]);
    }

    public function is_recommend (Goods $good) {
        $is_recommend = $good->is_recommend === 0 ? 1 : 0;
        $good->is_recommend = $is_recommend;
        $good->save();
        return $this->response->array([
            'success' => true,
            'message' => $is_recommend === 0 ? '取消推荐' : '推荐成功',
            'is_recommend' => $is_recommend
        ]);
    }
}

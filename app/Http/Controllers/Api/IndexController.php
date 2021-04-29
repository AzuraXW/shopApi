<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Goods;
use App\Models\Slides;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    //
    public function index () {
        // 轮播图数据
        $slides = Slides::where('status', 1)->orderBy('seq')->get();
        // 分类数据
        $categories = cache_category();
        // 推荐商品数据
        $recommendGoods = Goods::where('is_on', 1)->get();

        return $this->response->array([
            'slides' => $slides,
            'categories' => $categories,
            'recommendgoods' => $recommendGoods
        ]);
    }
}

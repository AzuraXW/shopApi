<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SlidesRequest;
use App\Models\slides;
use App\Transformers\SlidesTransformer;
use Illuminate\Http\Request;

class SlidesController extends BaseController
{
    /**
     * 轮播图列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $status = $request->input('status');
        $limit = $request->input('limit');
        $slides = Slides::when($status == '0' || $status == '1', function ($query) use ($status) {
            return $query->where('status', $status);
        })->when($title, function ($query) use ($title) {
            return $query->where('title', $title);
        })->paginate($limit);

        return $this->response->paginator($slides, new SlidesTransformer());
    }

    /**
     * 添加轮播图
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SlidesRequest $request)
    {
        $max = Slides::max('seq') ?? 0;
        $max++;
        $request->offsetSet('seq', $max);
        Slides::create($request->all());
        return $this->response->array([
            'success' => true,
            'message' => '添加成功'
        ]);
    }

    /**
     * 轮播图详情
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Slides $slide)
    {
        return $this->response->item($slide, new SlidesTransformer());
    }

    /**
     * 更新轮播图
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slides $slide)
    {
        if (!count($request->all()) > 0) {
            return $this->response->array([
               'success' => false,
               'message' => '缺少需要更新的参数'
            ])->setStatusCode(422);
        }
        $slide->update($request->all());
        $slide->save();
        return $this->response->array([
            'success' => true,
            'message' => '更新成功'
        ]);
    }

    /**
     * 删除轮播图
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status(Slides $slide)
    {
        $status = $slide->status == 0 ? 1 : 0;
        $slide->status = $status;
        $slide->save();
        return $this->response->array([
            'success' => true,
            'message' => $status === 0 ? '成功禁用该轮播图' : '成功启用该轮播图'
        ]);
    }
}

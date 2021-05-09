<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Comments;
use App\Models\Goods;
use App\Transformers\CommentTransformer;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    /**
     * 获取评论列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rate = $request->query('rate');
        $goods_title = $request->query('goods_title');
        $limit = $request->query('limit');
        $comments = Comments::when($rate, function ($query) use ($rate) {
            return $query->where('rate', $rate);
        })->when($goods_title, function ($query) use ($goods_title) {
            $goods_ids = Goods::where('title', 'like', "%$goods_title%")->pluck('id');
            return $query->whereIn('goods_id', $goods_ids);
        })->paginate($limit);
        return $this->response->paginator($comments, new CommentTransformer())->setMeta([
            'success' => true,
            'message' => '成功获取评论'
        ]);
    }

    /**
     * 商品详情
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Comments $comment)
    {
        return $this->response->item($comment, new CommentTransformer())->setMeta([
            'success' => true,
            'message' => '获取成功'
        ]);
    }

    // 商家回复
    public function reply(Request $request, Comments $comment)
    {
        $reply = $request->input('reply');
        if ($reply == '' || strlen($reply) > 255) {
            return $this->response->array([
                'success' => false,
                'message' => '回复内容的长度是0-255'
            ])->setStatusCode(422);
        }
        $comment->reply = $reply;
        $comment->save();
        return $this->response->array([
            'success' => true,
            'message' => '回复成功'
        ]);
    }
}

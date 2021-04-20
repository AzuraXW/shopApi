<?php

namespace App\Transformers;

use App\Models\Comment;
use App\Models\Goods;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract {
    protected $availableIncludes = ['user', 'goods'];

    public function transform(Comment $comment) {
        $pics_url = [];
        if ($comment->pics) {
            foreach ($comment->pics as $pic) {
                array_push($pics_url, oss_url($pic));
            }
        }
        return [
            'id' => $comment->id,
            'goods_id' => $comment->goods_id,
            'rate' => $comment->rate,
            'content' => $comment->content,
            'reply' => $comment->reply,
            'pics' => $comment->pics,
            'pics_url' => $pics_url,
            'created_at' => $comment->created_at,
            'updated_at' => $comment->updated_at
        ];
    }

    public function includeGoods (Comment $comment) {
        return $this->item($comment->goods, new GoodsTransformer());
    }
    public function includeUser (Comment $comment) {
        return $this->item($comment->user, new UserTransformer());
    }
}

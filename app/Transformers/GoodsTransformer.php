<?php

namespace App\Transformers;


use App\Models\Goods;
use League\Fractal\TransformerAbstract;

class GoodsTransformer extends TransformerAbstract {
    protected $availableIncludes = ['category', 'user', 'comment'];
    public function transform(Goods $goods) {
        $pics_url = [];
        foreach ($goods->pics as $pic) {
            array_push($pics_url, oss_url($pic));
        }

        return [
            'id' => $goods->id,
            'category_id' => $goods->category_id,
            'description' => $goods->description,
            'price' => $goods->price,
            'stock' => $goods->stock,
            'cover' => $goods->cover,
            'cover_url' => oss_url($goods->cover),
            'pics' => $goods->pics,
            'pics_url' => $pics_url,
            'details' => $goods->details,
            'title' => $goods->title,
            'is_on' => $goods->is_on,
            'is_recommend' => $goods->is_recommend
        ];
    }

    public function includeCategory (Goods $good) {
        return $this->item($good->category, new CategoryTransformer());
    }
    public function includeUser (Goods $good) {
        return $this->item($good->user, new UserTransformer());
    }
    public function includeComment (Goods $good) {
        return $this->collection($good->comment, new CommentTransformer());
    }
}

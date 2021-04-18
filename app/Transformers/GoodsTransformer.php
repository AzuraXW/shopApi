<?php

namespace App\Transformers;


use App\Models\Goods;
use League\Fractal\TransformerAbstract;

class GoodsTransformer extends TransformerAbstract {
    protected $availableIncludes = ['category', 'user'];
    public function transform(Goods $goods) {
        return [
            'category_id' => $goods->category_id,
            'description' => $goods->description,
            'price' => $goods->price,
            'stock' => $goods->stock,
            'cover' => $goods->cover,
            'pics' => $goods->pics,
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
}

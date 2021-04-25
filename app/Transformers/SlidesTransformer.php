<?php

namespace App\Transformers;

use App\Models\Slides;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class SlidesTransformer extends TransformerAbstract {
    public function transform(Slides $slide) {
        return [
            'id' => $slide->id,
            'title' => $slide->title,
            'url' => $slide->url,
            'img' => $slide->img,
            'img_url' => oss_url($slide->img),
            'status' => $slide->status,
            'seq' => $slide->seq
        ];
    }
}

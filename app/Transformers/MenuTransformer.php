<?php

namespace App\Transformers;

use App\Models\Menu;
use League\Fractal\TransformerAbstract;

class MenuTransformer extends TransformerAbstract {
    public function transform(Menu $menu) {
        return [
            'id' => $menu->id,
            'title' => $menu->title,
            'path' => $menu->path,
            'keep_alive' => $menu->keep_alive,
            'roles' => $menu->roles()->select('roles.id', 'name', 'cn_name')->get(),
            'children' => $menu
                ->children()
                ->with([
                    'roles' => function ($query) {
                        return $query->select('roles.id', 'name', 'cn_name');
                    }
                ])
                ->select('id', 'title', 'path', 'keep_alive')
                ->get()
        ];
    }
}

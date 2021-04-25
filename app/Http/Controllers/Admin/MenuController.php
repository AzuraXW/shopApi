<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class MenuController extends BaseController
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        if ($type === 'all') {
            $category = cache_menu();
        } else {
            $category = cache_menu_all();
        }

        return $this->response->array([
            'success' => true,
            'message' => '成功获取菜单',
            'data' => $category
        ]);
    }
}

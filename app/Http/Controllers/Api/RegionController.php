<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Chain;
use Illuminate\Http\Request;

class RegionController extends BaseController
{
    // 返回省份
    public function show (Request $request) {
        $pid = $request->query('pid', '100000');
        $region = province($pid);
        return $region;
    }
}

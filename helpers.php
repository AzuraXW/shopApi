<?php
use App\Models\Category;
use App\Models\Chain;
if (!function_exists('categoryTree')) {
    function categoryTree ($status = false) {
        $categories = Category::when($status !== false, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->where('pid', 0)
            ->with([
                'children.children.brand' => function ($query) use ($status) {
                    return $query->when($status !== false, function ($query) use ($status) {
                        return $query->where('status', $status);
                    });
                }
            ])
            ->get();
        return $categories;
    }
}

// 缓存未禁用的分类
if (!function_exists('cache_category')) {
    function cache_category () {
        return cache()->rememberForever('cache_category', function () {
            return categoryTree(true);
        });
    }
}

// 缓存所有的分类
if (!function_exists('cache_category_all')) {
    function cache_category_all () {
        return cache()->rememberForever('cache_category_all', function () {
           return categoryTree();
        });
    }
}

// 忘记缓存的分类
if (!function_exists('forget_cache_category')) {
    function forget_cache_category () {
        cache()->forget('cache_category');
        cache()->forget('cache_category_all');
    }
}

if (! function_exists('error')) {
    function error($message, $code = -1)
    {
        throw new \App\Exceptions\ApiException($message, $code);
    }
}

if (!function_exists('oss_url')) {
    function oss_url ($key) {
        if (empty($key)) return  '';

        if (strpos($key, 'http://') !== false
            || strpos($key, 'https://') !== false
            || strpos($key, 'data:image')) {
            return $key;
        }
        return config('filesystems')['disks']['oss']['bucket_url'] . '/' . $key;
    }
}

// 缓存所有的省份
if (!function_exists('region_cache')) {
    function province ($pid) {
        return cache()->rememberForever('region_cache' . $pid, function () use ($pid) {
            return Chain::where('pid', $pid)->get();
        });
    }
}

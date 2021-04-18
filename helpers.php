<?php
use App\Models\Category;
if (!function_exists('categoryTree')) {
    function categoryTree ($status = false) {
        $categories = Category::when($status !== false, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->where('pid', 0)
            ->with([
                'children.children' => function ($query) use ($status) {
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
        return config('filesystems')['disks']['oss']['bucket_url'] . '/' . $key;
    }
}

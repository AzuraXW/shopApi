<?php

namespace App\Providers;

use App\Facades\Express\Express;
use App\Models\Category;
use App\Observers\CategoryObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 注册自定义门面
        $this->app->singleton('Express', function () {
            return new Express();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Category::observe(CategoryObserver::class);
    }
}

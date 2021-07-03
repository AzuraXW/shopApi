<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('pid')->default(0)->comment('父级菜单');
            $table->string('title')->comment('菜单名称');
            $table->string('icon')->nullable()->comment('菜单图标');
            $table->string('path')->comment('路由地址');
            $table->string('component')->comment('组件名称');
            $table->string('name')->nullable()->comment('路由名称');
            $table->string('redirect')->nullable()->comment('重定向地址');
            $table->tinyInteger('always_show')->default(1)->comment('子菜单个数为1个时是否显示');
            $table->tinyInteger('keep_alive')->default(0)->comment('组件缓存');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }
}

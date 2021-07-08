<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecParamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spec_param', function (Blueprint $table) {
            // 商品规格参数表
            $table->id();
            $table->bigInteger('category_id')->comment('商品分类id');
            $table->bigInteger('group_id')->comment('规格组id');
            $table->string('name')->comment('参数名');
            $table->tinyInteger('numeric')->comment('是否是数字类型参数');
            $table->string('unit')->nullable()->comment('数字类型参数的单位');
            $table->tinyInteger('generic')->comment('是否是SKU通用规格');
            $table->tinyInteger('searching')->comment('是否用于搜索过滤');
            $table->string('segments')->default('')->comment('区间 (数值类型参数的预设区间值，如果需要搜索，则添加分段间隔值，如CPU频率间隔：0.5-1.0)');
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
        Schema::dropIfExists('spec_param');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spu', function (Blueprint $table) {
            // 商品集
            $table->id();
            $table->string('name')->comment('商品名称');
            $table->string('sub_title')->comment('副标题');
            $table->bigInteger('cid')->comment('分类id');
            $table->bigInteger('brand_id')->nullable()->comment('商品名称');
            $table->tinyInteger('saleable')->default(1)->comment('是否上架（0-下架，1-上架）');
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
        Schema::dropIfExists('spu');
    }
}

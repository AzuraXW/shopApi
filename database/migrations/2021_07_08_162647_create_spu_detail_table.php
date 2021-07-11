<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpuDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spu_detail', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('spu_id')->comment('SPU Id');
            $table->text('description')->comment('商品描述信息');
            $table->string('generic_spec')->comment('通用规格键值对 (json格式)');
            $table->string('special_spec')->comment('特有规格可选值 (json格式)');
            $table->string('packing_list')->nullable()->comment('包装清单');
            $table->string('after_service')->nullable()->comment('售后服务');
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
        Schema::dropIfExists('spu_detail');
    }
}

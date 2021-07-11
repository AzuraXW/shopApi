<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sku', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('spu_id')->comment('SPU Id');
            $table->string('title')->comment('商品标题');
            $table->string('images')->comment('商品图片 (多个图片用,号分割)');
            $table->bigInteger('stock')->comment('库存');
            $table->bigInteger('price')->comment('销售价格 (单位为分)');
            $table->string('indexes')->comment('特有规格参数在SPU规格模板中对应的下标组合(如1_0_0)');
            $table->string('own_spec')->comment('SKU的特有规格参数键值对');
            $table->tinyInteger('enable')->default(1)->comment('是否有效 (0-无效，1-有效)');
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
        Schema::dropIfExists('sku');
    }
}

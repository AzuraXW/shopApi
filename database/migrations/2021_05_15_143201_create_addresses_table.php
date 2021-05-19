<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('用户id');
            $table->string('name')->comment('收货人姓名');
            $table->integer('city_id')->comment('城市id');
            $table->string('address')->comment('详细地址');
            $table->string('phone')->comment('收货人手机号码');
            $table->string('email')->nullable()->comment('电子邮箱');
            $table->tinyInteger('is_default')->default(0)->comment('是否默认');
            $table->integer('alias')->nullable()->comment('地址别名');
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
        Schema::dropIfExists('addresses');
    }
}

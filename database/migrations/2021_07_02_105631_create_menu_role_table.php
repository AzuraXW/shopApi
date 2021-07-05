<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_role', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mid')->comment('菜单id');
            $table->unsignedBigInteger('rid')->comment('角色id');

            $table->foreign('mid')
                ->references('id')
                ->on('menu')
                ->onDelete('cascade');

            $table->foreign('rid')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['mid', 'rid'], 'menu_role_parimary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_role');
    }
}

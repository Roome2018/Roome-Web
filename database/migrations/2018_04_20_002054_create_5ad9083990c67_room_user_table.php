<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create5ad9083990c67RoomUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('room_user')) {
            Schema::create('room_user', function (Blueprint $table) {
                $table->integer('room_id')->unsigned()->nullable();
                $table->foreign('room_id', 'fk_p_146898_146889_user_r_5ad9083990d9f')->references('id')->on('rooms')->onDelete('cascade');
                $table->integer('user_id')->unsigned()->nullable();
                $table->foreign('user_id', 'fk_p_146889_146898_room_u_5ad9083990e97')->references('id')->on('users')->onDelete('cascade');
                
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_user');
    }
}

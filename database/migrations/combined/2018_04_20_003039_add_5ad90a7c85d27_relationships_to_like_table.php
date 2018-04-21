<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add5ad90a7c85d27RelationshipsToLikeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('likes', function(Blueprint $table) {
            if (!Schema::hasColumn('likes', 'user_id')) {
                $table->integer('user_id')->unsigned()->nullable();
                $table->foreign('user_id', '146900_5ad9093d3565f')->references('id')->on('users')->onDelete('cascade');
                }
                if (!Schema::hasColumn('likes', 'room_id')) {
                $table->integer('room_id')->unsigned()->nullable();
                $table->foreign('room_id', '146900_5ad9093d42a1d')->references('id')->on('rooms')->onDelete('cascade');
                }
                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('likes', function(Blueprint $table) {
            if(Schema::hasColumn('likes', 'user_id')) {
                $table->dropForeign('146900_5ad9093d3565f');
                $table->dropIndex('146900_5ad9093d3565f');
                $table->dropColumn('user_id');
            }
            if(Schema::hasColumn('likes', 'room_id')) {
                $table->dropForeign('146900_5ad9093d42a1d');
                $table->dropIndex('146900_5ad9093d42a1d');
                $table->dropColumn('room_id');
            }
            
        });
    }
}

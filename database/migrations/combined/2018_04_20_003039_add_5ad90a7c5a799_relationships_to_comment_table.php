<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add5ad90a7c5a799RelationshipsToCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function(Blueprint $table) {
            if (!Schema::hasColumn('comments', 'user_id')) {
                $table->integer('user_id')->unsigned()->nullable();
                $table->foreign('user_id', '146899_5ad908cadf429')->references('id')->on('users')->onDelete('cascade');
                }
                if (!Schema::hasColumn('comments', 'room_id')) {
                $table->integer('room_id')->unsigned()->nullable();
                $table->foreign('room_id', '146899_5ad908caed749')->references('id')->on('rooms')->onDelete('cascade');
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
        Schema::table('comments', function(Blueprint $table) {
            if(Schema::hasColumn('comments', 'user_id')) {
                $table->dropForeign('146899_5ad908cadf429');
                $table->dropIndex('146899_5ad908cadf429');
                $table->dropColumn('user_id');
            }
            if(Schema::hasColumn('comments', 'room_id')) {
                $table->dropForeign('146899_5ad908caed749');
                $table->dropIndex('146899_5ad908caed749');
                $table->dropColumn('room_id');
            }
            
        });
    }
}

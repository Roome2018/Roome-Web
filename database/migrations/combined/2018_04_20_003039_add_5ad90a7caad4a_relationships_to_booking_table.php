<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add5ad90a7caad4aRelationshipsToBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function(Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'room_id')) {
                $table->integer('room_id')->unsigned()->nullable();
                $table->foreign('room_id', '146901_5ad90a209e7e8')->references('id')->on('rooms')->onDelete('cascade');
                }
                if (!Schema::hasColumn('bookings', 'user_id')) {
                $table->integer('user_id')->unsigned()->nullable();
                $table->foreign('user_id', '146901_5ad90a20afe2b')->references('id')->on('users')->onDelete('cascade');
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
        Schema::table('bookings', function(Blueprint $table) {
            if(Schema::hasColumn('bookings', 'room_id')) {
                $table->dropForeign('146901_5ad90a209e7e8');
                $table->dropIndex('146901_5ad90a209e7e8');
                $table->dropColumn('room_id');
            }
            if(Schema::hasColumn('bookings', 'user_id')) {
                $table->dropForeign('146901_5ad90a20afe2b');
                $table->dropIndex('146901_5ad90a20afe2b');
                $table->dropColumn('user_id');
            }
            
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('events');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user1_id')->unsigned();
            $table->bigInteger('user2_id')->unsigned()->nullable();
            $table->bigInteger('event_type_id')->unsigned();
            $table->timestamp('created_at');
            $table->index('user1_id');
            $table->index('user2_id');
            $table->index('event_type_id');
            $table->foreign('user1_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user2_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('event_type_id')->references('id')->on('event_types')->onUpdate('cascade');
        });
    }
}

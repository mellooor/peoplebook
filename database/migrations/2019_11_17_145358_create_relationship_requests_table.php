<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationshipRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relationship_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user1_id')->unsigned();
            $table->bigInteger('user2_id')->unsigned();
            $table->index('user1_id');
            $table->index('user2_id');
            $table->foreign('user1_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user2_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relationship_requests');
    }
}

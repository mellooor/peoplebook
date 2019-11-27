<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeStartDateUser2IdAndIsRequestColumnsNullableInRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relationships', function (Blueprint $table) {
            $table->dropForeign('relationships_user2_id_foreign');
            $table->date('start_date')->nullable()->change();
            $table->bigInteger('user2_id')->unsigned()->nullable()->change();
            $table->boolean('is_request')->nullable()->change();
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
        Schema::table('relationships', function (Blueprint $table) {
            $table->dropForeign('relationships_user2_id_foreign');
            $table->date('start_date')->nullable(false)->change();
            $table->bigInteger('user2_id')->unsigned()->nullable(false)->change();
            $table->boolean('is_request')->nullable(false)->change();
            $table->foreign('user2_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewRelationshipIdColumnToActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->bigInteger('new_relationship_id')->nullable()->unsigned();
            $table->index('new_relationship_id');
            $table->foreign('new_relationship_id')->references('id')->on('relationships')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign('activities_new_relationship_id_foreign');
            $table->dropColumn('new_relationship_id');
        });
    }
}

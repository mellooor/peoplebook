<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteRelationshipTypeIdColumnFromUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_relationship_type_id_foreign');
            $table->dropColumn('relationship_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('relationship_type_id')->unsigned()->nullable();
            $table->index('relationship_type_id');
            $table->foreign('relationship_type_id')->references('id')->on('relationship_types')->onUpdate('cascade');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relationships', function (Blueprint $table) {
            $table->foreign('relationship_type_id')->references('id')->on('relationship_types')->onUpdate('cascade');
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
        Schema::table('relationships', function (Blueprint $table) {
            $table->dropForeign('relationships_relationship_types_relationship_type_id_foreign');
            $table->dropForeign('relationships_users_user1_id_foreign');
            $table->dropForeign('relationships_users_user2_id_foreign');
        });
    }
}

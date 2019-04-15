<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('relationship_type_id')->references('id')->on('relationship_types')->onUpdate('cascade');
            $table->foreign('privacy_type_id')->references('id')->on('privacy_types')->onUpdate('cascade');
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
            $table->dropForeign('users_relationship_type_id_foreign');
            $table->dropForeign('users_privacy_type_id_foreign');
        });
    }
}

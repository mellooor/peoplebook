<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemovePrivacyTypeIdColumnFromUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_privacy_type_id_foreign');
            $table->dropColumn('privacy_type_id');
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
            $table->bigInteger('privacy_type_id')->unsigned()->default(1);
            $table->index('privacy_type_id');
            $table->foreign('privacy_type_id')->references('id')->on('privacy_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}

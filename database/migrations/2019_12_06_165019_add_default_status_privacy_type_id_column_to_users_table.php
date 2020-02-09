<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultStatusPrivacyTypeIdColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('default_status_privacy_type_id')->unsigned()->default(1);
            $table->index('default_status_privacy_type_id');
            $table->foreign('default_status_privacy_type_id')->references('id')->on('privacy_types')->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropForeign('users_default_status_privacy_type_id_foreign');
            $table->dropColumn('default_status_privacy_type_id');
        });
    }
}

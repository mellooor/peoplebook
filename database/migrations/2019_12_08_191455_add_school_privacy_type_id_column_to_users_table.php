<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSchoolPrivacyTypeIdColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('school_privacy_type_id')->unsigned()->default(1);
            $table->index('school_privacy_type_id');
            $table->foreign('school_privacy_type_id')->references('id')->on('privacy_types')->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropForeign('users_school_privacy_type_id_foreign');
            $table->dropColumn('school_privacy_type_id');
        });
    }
}

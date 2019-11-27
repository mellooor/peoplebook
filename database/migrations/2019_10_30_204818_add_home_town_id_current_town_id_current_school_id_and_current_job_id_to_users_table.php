<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHomeTownIdCurrentTownIdCurrentSchoolIdAndCurrentJobIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('home_town_id')->unsigned()->nullable();
            $table->bigInteger('current_town_id')->unsigned()->nullable();
            $table->bigInteger('current_school_id')->unsigned()->nullable();
            $table->bigInteger('current_job_id')->unsigned()->nullable();
            $table->index('home_town_id');
            $table->index('current_town_id');
            $table->index('current_school_id');
            $table->index('current_job_id');
            $table->foreign('home_town_id')->references('id')->on('places')->onUpdate('cascade');
            $table->foreign('current_town_id')->references('id')->on('places')->onUpdate('cascade');
            $table->foreign('current_school_id')->references('id')->on('schools')->onUpdate('cascade');
            $table->foreign('current_job_id')->references('id')->on('jobs')->onUpdate('cascade');
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
            $table->dropForeign('users_home_town_id_foreign');
            $table->dropForeign('users_current_town_id_foreign');
            $table->dropForeign('users_current_school_id_foreign');
            $table->dropForeign('users_current_job_id_foreign');
            $table->dropColumn('home_town_id');
            $table->dropColumn('current_town_id');
            $table->dropColumn('current_school_id');
            $table->dropColumn('current_job_id');
        });
    }
}

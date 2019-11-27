<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveJobTitleIdColumnAndAddJobTitleColumnToJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropForeign('jobs_job_title_id_foreign');
            $table->dropColumn('job_title_id');
            $table->string('job_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('job_title');
            $table->bigInteger('job_title_id')->unsigned();
            $table->index('job_title_id');
            $table->foreign('job_title_id')->references('id')->on('job_titles')->onUpdate('cascade');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhotoCommentIdToActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->bigInteger('photo_comment_id')->nullable()->unsigned();
            $table->index('photo_comment_id');
            $table->foreign('photo_comment_id')->references('id')->on('photo_comments')->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropForeign('activities_photo_comment_id_foreign');
            $table->dropColumn('photo_comment_id');
        });
    }
}

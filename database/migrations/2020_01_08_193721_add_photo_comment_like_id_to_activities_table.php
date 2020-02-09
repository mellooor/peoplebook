<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhotoCommentLikeIdToActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->bigInteger('photo_comment_like_id')->nullable()->unsigned();
            $table->index('photo_comment_like_id');
            $table->foreign('photo_comment_like_id')->references('id')->on('photo_comment_likes')->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropForeign('activities_photo_comment_like_id_foreign');
            $table->dropColumn('photo_comment_like_id');
        });
    }
}

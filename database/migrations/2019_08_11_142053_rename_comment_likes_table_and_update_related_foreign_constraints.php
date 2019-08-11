<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCommentLikesTableAndUpdateRelatedForeignConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comment_likes', function (Blueprint $table) {
            $table->dropForeign('comment_likes_comment_id_foreign');
            $table->dropForeign('comment_likes_user_id_foreign');
            $table->dropIndex('comment_likes_user_id_index');
            $table->dropIndex('comment_likes_comment_id_index');
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign('activities_status_comment_like_id_foreign');
        });


        Schema::rename('comment_likes', 'status_comment_likes');

        Schema::table('status_comment_likes', function (Blueprint $table) {
            $table->index('comment_id');
            $table->index('user_id');
            $table->foreign('comment_id')->references('id')->on('status_comments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->foreign('status_comment_like_id')->references('id')->on('status_comment_likes')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('status_comment_likes', function (Blueprint $table) {
            $table->dropForeign('status_comment_likes_comment_id_foreign');
            $table->dropForeign('status_comment_likes_user_id_foreign');
            $table->dropIndex('status_comment_likes_user_id_index');
            $table->dropIndex('status_comment_likes_comment_id_index');
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign('activities_status_comment_like_id_foreign');
        });

        Schema::rename('status_comment_likes', 'comment_likes');

        Schema::table('comment_likes', function (Blueprint $table) {
            $table->index('comment_id');
            $table->index('user_id');
            $table->foreign('comment_id')->references('id')->on('status_comments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->foreign('status_comment_like_id')->references('id')->on('comment_likes')->onUpdate('cascade')->onDelete('cascade');
        });

    }
}

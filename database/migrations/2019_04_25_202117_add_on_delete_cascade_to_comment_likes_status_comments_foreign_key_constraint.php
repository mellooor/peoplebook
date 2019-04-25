<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnDeleteCascadeToCommentLikesStatusCommentsForeignKeyConstraint extends Migration
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
            $table->foreign('comment_id')->references('id')->on('status_comments')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comment_likes', function (Blueprint $table) {
            $table->dropForeign('comment_likes_comment_id_foreign');
            $table->foreign('comment_id')->references('id')->on('status_comments')->onUpdate('cascade');
        });
    }
}

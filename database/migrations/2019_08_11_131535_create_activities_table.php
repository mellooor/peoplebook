<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            // Define Columns
            $table->bigIncrements('id');
            $table->bigInteger('user1_id')->unsigned();
            $table->bigInteger('user2_id')->unsigned()->nullable($value = true);
            $table->bigInteger('created_status_id')->unsigned()->nullable($value = true);
            $table->bigInteger('status_comment_id')->unsigned()->nullable($value = true);
            $table->bigInteger('status_like_id')->unsigned()->nullable($value = true);
            $table->bigInteger('status_comment_like_id')->unsigned()->nullable($value = true);
            $table->bigInteger('uploaded_photo_id')->unsigned()->nullable($value = true);
            $table->bigInteger('updated_profile_picture_photo_id')->unsigned()->nullable($value = true);
            $table->bigInteger('new_friendship_id')->unsigned()->nullable($value = true);

            // Define Unique Indexes
            $table->unique([
                'created_status_id',
                'status_comment_id',
                'status_like_id',
                'status_comment_like_id',
                'uploaded_photo_id',
                'updated_profile_picture_photo_id',
                'new_friendship_id'
            ], 'entities_index_unique');

            // Define Foreign Constraints
            $table->foreign('user1_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user2_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_status_id')->references('id')->on('statuses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('status_comment_id')->references('id')->on('status_comments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('status_like_id')->references('id')->on('status_likes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('status_comment_like_id')->references('id')->on('comment_likes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('uploaded_photo_id')->references('id')->on('photos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_profile_picture_photo_id')->references('id')->on('photos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('new_friendship_id')->references('id')->on('friendships')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}

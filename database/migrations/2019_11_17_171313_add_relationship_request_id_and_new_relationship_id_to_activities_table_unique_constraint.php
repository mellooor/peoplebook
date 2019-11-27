<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationshipRequestIdAndNewRelationshipIdToActivitiesTableUniqueConstraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            // Every foreign constaint that involves the unique columns needs to be dropped beforehand.
            $table->dropForeign('activities_created_status_id_foreign');
            $table->dropForeign('activities_status_comment_id_foreign');
            $table->dropForeign('activities_status_like_id_foreign');
            $table->dropForeign('activities_status_comment_like_id_foreign');
            $table->dropForeign('activities_uploaded_photo_id_foreign');
            $table->dropForeign('activities_updated_profile_picture_photo_id_foreign');
            $table->dropForeign('activities_new_friendship_id_foreign');

            $table->dropUnique('entities_index_unique');
            $table->unique([
                'created_status_id',
                'status_comment_id',
                'status_like_id',
                'status_comment_like_id',
                'uploaded_photo_id',
                'updated_profile_picture_photo_id',
                'new_friendship_id',
                'relationship_request_id',
                'new_relationship_id'
            ], 'entities_index_unique');

            // Re-include foreign keys that had to be removed in order to delete the unique constraint.
            $table->foreign('created_status_id')->references('id')->on('statuses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('status_comment_id')->references('id')->on('status_comments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('status_like_id')->references('id')->on('status_likes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('status_comment_like_id')->references('id')->on('status_comment_likes')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('activities', function (Blueprint $table) {
            // Every foreign constaint that involves the unique columns needs to be dropped beforehand.
            $table->dropForeign('activities_created_status_id_foreign');
            $table->dropForeign('activities_status_comment_id_foreign');
            $table->dropForeign('activities_status_like_id_foreign');
            $table->dropForeign('activities_status_comment_like_id_foreign');
            $table->dropForeign('activities_uploaded_photo_id_foreign');
            $table->dropForeign('activities_updated_profile_picture_photo_id_foreign');
            $table->dropForeign('activities_new_friendship_id_foreign');
            $table->dropForeign('activities_relationship_request_id_foreign');
            $table->dropForeign('activities_new_relationship_id_foreign');

            $table->dropUnique('entities_index_unique');
            $table->unique([
                'created_status_id',
                'status_comment_id',
                'status_like_id',
                'status_comment_like_id',
                'uploaded_photo_id',
                'updated_profile_picture_photo_id',
                'new_friendship_id',
            ], 'entities_index_unique');

            // Re-include foreign keys that had to be removed in order to delete the unique constraint.
            $table->foreign('created_status_id')->references('id')->on('statuses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('status_comment_id')->references('id')->on('status_comments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('status_like_id')->references('id')->on('status_likes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('status_comment_like_id')->references('id')->on('status_comment_likes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('uploaded_photo_id')->references('id')->on('photos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_profile_picture_photo_id')->references('id')->on('photos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('new_friendship_id')->references('id')->on('friendships')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('relationship_request_id')->references('id')->on('relationship_requests')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('new_relationship_id')->references('id')->on('relationships')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}

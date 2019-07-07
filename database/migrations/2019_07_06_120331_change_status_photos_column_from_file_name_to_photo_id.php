<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStatusPhotosColumnFromFileNameToPhotoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('status_photos', function (Blueprint $table) {
            $table->dropColumn('file_name');
            $table->bigInteger('photo_id')->unsigned();
            $table->index('photo_id');
            $table->foreign('photo_id')->references('id')->on('photos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('status_photos', function (Blueprint $table) {
            $table->dropForeign('status_photos_photo_id_foreign');
            $table->dropIndex('status_photos_photo_id_index');
            $table->dropColumn('photo_id');
            $table->string('file_name');
        });
    }
}

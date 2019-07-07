<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->foreign('uploader_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('photo_types')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropForeign('photos_uploader_id_foreign');
            $table->dropForeign('photos_type_id_foreign');
        });
    }
}

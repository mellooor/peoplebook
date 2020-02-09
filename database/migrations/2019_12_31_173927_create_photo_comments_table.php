<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('author_id')->unsigned();
            $table->bigInteger('raw_photo_id')->unsigned();
            $table->mediumText('content');
            $table->timestamps();
            $table->index('author_id');
            $table->index('raw_photo_id');
            $table->foreign('author_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('raw_photo_id')->references('id')->on('photos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photo_comments');
    }
}

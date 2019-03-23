<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToStatusCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('status_comments', function (Blueprint $table) {
            $table->index('author_id');
            $table->index('status_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('status_comments', function (Blueprint $table) {
            $table->dropIndex('status_comments_author_id_index');
            $table->dropIndex('status_comments_status_id_index');
        });
    }
}

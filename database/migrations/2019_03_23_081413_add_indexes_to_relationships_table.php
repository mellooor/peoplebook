<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relationships', function (Blueprint $table) {
            $table->index('relationship_type_id');
            $table->index('user1_id');
            $table->index('user2_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relationships', function (Blueprint $table) {
            $table->dropIndex('relationships_relationship_type_id_index');
            $table->dropIndex('relationships_user1_id_index');
            $table->dropIndex('relationships_user2_id_index');
        });
    }
}

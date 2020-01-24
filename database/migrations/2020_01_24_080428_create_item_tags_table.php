<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_tag', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });

        Schema::table('item_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->after('id');
            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedBigInteger('tag_id')->after('tag_id');
            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unique(['item_id', 'tag_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_tag', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['tag_id']);
            $table->dropUnique(['item_id', 'tag_id']);
        });

        Schema::dropIfExists('item_tag');
    }
}

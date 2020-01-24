<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemAromaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_aroma', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });

        Schema::table('item_aroma', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->after('id');
            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedBigInteger('aroma_id')->after('item_id');
            $table->foreign('aroma_id')
                ->references('id')
                ->on('aromas')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unique(['item_id', 'aroma_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_aroma', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['aroma_id']);
            $table->dropUnique(['item_id', 'aroma_id']);
        });

        Schema::dropIfExists('item_aroma');
    }
}

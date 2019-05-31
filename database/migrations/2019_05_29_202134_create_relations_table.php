<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });

        Schema::table('relations', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->after('id');
            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('contractor_item_id')->unique()->after('item_id');
            $table->foreign('contractor_item_id')
                ->references('id')
                ->on('contractor_items')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relations', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['contractor_item_id']);
        });

        Schema::dropIfExists('relations');
    }
}

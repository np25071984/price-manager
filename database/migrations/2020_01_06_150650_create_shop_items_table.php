<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_items', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('shop_id');
            $table->float('price')->nullable();
            $table->timestamps();
        });

        Schema::table('shop_items', function (Blueprint $table) {
            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('shop_id')
                ->references('id')
                ->on('shops')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unique(['item_id', 'shop_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_items', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['shop_id']);
        });

        Schema::dropIfExists('shop_items');
    }
}

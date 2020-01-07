<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobsPriceGenerationStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs_price_generation_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('status_id');
            $table->string('message', 1024);
        });

        Schema::table('jobs_price_generation_status', function (Blueprint $table) {
            $table->unsignedBigInteger('shop_id')->after('id');
            $table->foreign('shop_id')->references('id')->on('shops')->onUpdate('cascade')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs_price_generation_status', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
        });

        Schema::dropIfExists('jobs_price_generation_status');

    }
}

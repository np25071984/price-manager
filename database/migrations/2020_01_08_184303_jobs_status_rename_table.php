<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobsStatusRenameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs_price_processing_status', function (Blueprint $table) {
            $table->integer('contractor_id')->nullable()->unsigned();
            $table->integer('status_id');
            $table->string('message', 1024);
        });

        Schema::table('jobs_price_processing_status', function (Blueprint $table) {
            $table->foreign('contractor_id')->references('id')->on('contractors')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs_price_processing_status', function (Blueprint $table) {
            $table->dropForeign(['contractor_id']);
        });

        Schema::dropIfExists('jobs_price_processing_status');
    }
}

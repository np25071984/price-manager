<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobsStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('status_id');
            $table->string('message', 1024);
        });

        Schema::table('jobs_status', function (Blueprint $table) {
            $table->unsignedBigInteger('contractor_id')->nullable()->after('id');
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
        Schema::table('jobs_status', function (Blueprint $table) {
            $table->dropForeign(['contractor_id']);
        });

        Schema::dropIfExists('jobs_status');
    }
}

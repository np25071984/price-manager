<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractorItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contractor_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->float('price');
            $table->timestamps();
        });

        Schema::table('contractor_items', function (Blueprint $table) {
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
        Schema::table('contractor_items', function (Blueprint $table) {
            $table->dropForeign(['contractor_id']);
        });

        Schema::dropIfExists('contractor_items');
    }
}

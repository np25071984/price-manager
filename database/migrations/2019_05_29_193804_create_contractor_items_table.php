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
            $table->string('article', 32)->nullable();
            $table->string('name');
            $table->float('price');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['article', 'name']);
        });

        Schema::table('contractor_items', function (Blueprint $table) {
            $table->unsignedBigInteger('contractor_id')->after('id');
            $table->foreign('contractor_id')
                ->references('id')
                ->on('contractors')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unique(['contractor_id', 'article']);
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
            $table->dropUnique(['contractor_id', 'article']);
            $table->dropForeign(['contractor_id']);
        });

        Schema::dropIfExists('contractor_items');
    }
}

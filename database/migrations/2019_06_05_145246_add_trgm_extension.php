<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrgmExtension extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');
        DB::statement('CREATE INDEX items_name_trigram ON items USING gist(name gist_trgm_ops);');
        DB::statement('CREATE INDEX contractor_items_name_trigram ON contractor_items USING gist(name gist_trgm_ops);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP INDEX IF EXISTS items_name_trigram');
        DB::statement('DROP INDEX IF EXISTS contractor_items_name_trigram');
        // DB::statement('DROP EXTENSION IF EXISTS pg_trgm');
    }
}

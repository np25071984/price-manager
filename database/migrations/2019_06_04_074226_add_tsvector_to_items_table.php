<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTsvectorToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE OR REPLACE FUNCTION item_trigger() RETURNS trigger AS $$
                BEGIN
                  new.tsvector_token :=
                     to_tsvector('pg_catalog.english', coalesce(new.name,'')) ||
                     to_tsvector('pg_catalog.russian', coalesce(new.name,''));
                  return new;
                END
                $$ LANGUAGE plpgsql;
            ");

        DB::statement("ALTER TABLE items ADD COLUMN tsvector_token TSVECTOR");
        DB::statement("UPDATE items SET tsvector_token = to_tsvector('english', name) || to_tsvector('russian', name)");
        DB::statement("CREATE INDEX vs_item_vector_gin ON items USING GIN(tsvector_token)");
        DB::statement("CREATE TRIGGER ts_item_vector_update BEFORE INSERT OR UPDATE ON items FOR EACH ROW EXECUTE PROCEDURE item_trigger()");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP TRIGGER IF EXISTS ts_item_vector_update ON items");
        DB::statement("DROP INDEX IF EXISTS vs_item_vector_gin");
        DB::statement("ALTER TABLE items DROP COLUMN tsvector_token");

        DB::statement("DROP FUNCTION IF EXISTS item_trigger;");
    }
}

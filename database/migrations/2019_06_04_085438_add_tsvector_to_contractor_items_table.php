<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTsvectorToContractorItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE contractor_items ADD COLUMN tsvector_token TSVECTOR");
        DB::statement("UPDATE contractor_items SET tsvector_token = to_tsvector('english', name) || to_tsvector('russian', name)");
        DB::statement("CREATE INDEX vs_contractor_item_vector_gin ON contractor_items USING GIN(tsvector_token)");
        DB::statement("CREATE TRIGGER ts_contractor_item_vector_update BEFORE INSERT OR UPDATE ON contractor_items FOR EACH ROW EXECUTE PROCEDURE item_trigger()");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP TRIGGER IF EXISTS ts_contractor_item_vector_update ON contractor_items");
        DB::statement("DROP INDEX IF EXISTS vs_contractor_item_vector_gin");
        DB::statement("ALTER TABLE contractor_items DROP COLUMN tsvector_token");
    }
}

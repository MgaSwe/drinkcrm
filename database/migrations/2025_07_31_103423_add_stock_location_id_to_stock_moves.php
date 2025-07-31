<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Spalte nur hinzufügen, wenn sie noch nicht existiert
        if (!Schema::hasColumn('stock_moves', 'stock_location_id')) {
            Schema::table('stock_moves', function (Blueprint $t) {
                $t->foreignId('stock_location_id')
                  ->nullable() // falls schon Daten existieren
                  ->constrained('stock_locations')
                  ->nullOnDelete()
                  ->after('beverage_id');

                $t->index('stock_location_id');
            });
        }

        // Optional: Falls es eine alte Spalte "from_location_id" gibt, Werte übernehmen
        if (Schema::hasColumn('stock_moves', 'from_location_id')) {
            DB::statement('
                UPDATE stock_moves
                SET stock_location_id = from_location_id
                WHERE stock_location_id IS NULL
            ');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('stock_moves', 'stock_location_id')) {
            Schema::table('stock_moves', function (Blueprint $t) {
                $t->dropForeign(['stock_location_id']);
                $t->dropIndex(['stock_location_id']);
                $t->dropColumn('stock_location_id');
            });
        }
    }
};

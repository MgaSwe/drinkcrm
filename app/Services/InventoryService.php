<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Summe aller Moves für ein Getränk an einem Ort.
     * Erwartung: quantity ist + (Eingang) / - (Entnahme)
     */
    public function stockAt(int $beverageId, int $stockLocationId): int
    {
        return (int) DB::table('stock_moves')
            ->where('beverage_id', $beverageId)
            ->where('stock_location_id', $stockLocationId)
            ->sum('quantity');
    }
}

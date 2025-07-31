<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMove extends Model
{
    // Erlaube genau die Felder, die via create() kommen
    protected $fillable = [
        'beverage_id',
        'stock_location_id',   // wenn du dieses Feld genutzt hast
        'from_location_id',    // falls noch gebraucht
        'to_location_id',      // falls noch gebraucht
        'type',                // z.B. 'OUT'
        'quantity',
        'unit_cost_cents',
        'occurred_at',
        'user_id',
        'reason',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];
}

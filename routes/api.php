<?php

use App\Http\Controllers\StockMoveController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; 
use App\Services\JiraClient;

Route::get('/health', fn () => ['ok' => true]);

Route::post('/jira/test-issue', function (Request $req, JiraClient $jira) {
    $summary = $req->input('summary', 'DrinkCRM Test');
    $desc    = $req->input('description', 'Erstellt aus DrinkCRM');
    return $jira->createIssue($summary, $desc);
});

Route::get('/health', fn () => ['ok' => true]);

// Wareneingang (IN)
Route::post('/stock-moves/in', [StockMoveController::class, 'in']);

// Entnahme (OUT) + Jira-Check
Route::post('/stock-moves/consume', [StockMoveController::class, 'consume']);

// *** Nur zum Testen: Seed minimaler Stammdaten ***
Route::post('/dev/seed', function () {
    $brand = \App\Models\Brand::firstOrCreate(['name' => 'Generic']);
    $cat   = \App\Models\BeverageCategory::firstOrCreate(['name' => 'Softdrink']);
    $bev   = \App\Models\Beverage::firstOrCreate(
        ['name' => 'Cola'],
        ['brand_id' => $brand->id, 'beverage_category_id' => $cat->id, 'min_level' => 5]
    );
    $loc   = \App\Models\StockLocation::firstOrCreate(['name' => 'Kühlschrank EG']);

    // einmal Startbestand 6 buchen (falls noch nicht vorhanden)
    if (! \App\Models\StockMove::where([
        'beverage_id' => $bev->id,
        'stock_location_id' => $loc->id,
        'type' => 'in',
        'quantity' => 6,
    ])->exists()) {
        \App\Models\StockMove::create([
            'beverage_id' => $bev->id,
            'stock_location_id' => $loc->id,
            'type' => 'in',
            'quantity' => 6,
        ]);
    }

    return [
        'brand_id' => $brand->id,
        'category_id' => $cat->id,
        'beverage_id' => $bev->id,
        'stock_location_id' => $loc->id,
        'min_level' => $bev->min_level,
        'seeded' => true,
    ];
});

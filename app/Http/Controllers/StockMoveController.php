<?php

namespace App\Http\Controllers;

use App\Jobs\CreateLowStockIssueJob;
use App\Models\Beverage;
use App\Models\StockMove;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class StockMoveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function consume(Request $r)
    {
        $data = $r->validate([
            'beverage_id'       => ['required','integer','exists:beverages,id'],
            'stock_location_id' => ['required','integer','exists:stock_locations,id'],
            'quantity'          => ['required','integer','min:1'],
        ]);

        DB::transaction(function () use ($data) {
            // OUT = negative Menge speichern, weil eure on_hand-Summe raw SUM(quantity) ist
            $move = StockMove::create([
                'beverage_id'       => $data['beverage_id'],
                'stock_location_id' => $data['stock_location_id'],
                'type'              => 'OUT',
                'quantity'          => -abs($data['quantity']),
            ]);

            $onHand = StockMove::where('beverage_id', $data['beverage_id'])
                ->where('stock_location_id', $data['stock_location_id'])
                ->sum('quantity');

            $bev = Beverage::find($data['beverage_id']);
            if ($bev && $onHand < (int)$bev->min_level) {
                Log::info("LOW-STOCK: dispatch for bev {$bev->id} @loc {$data['stock_location_id']} on_hand={$onHand} min={$bev->min_level}");
CreateLowStockIssueJob::dispatch($bev->id, $data['stock_location_id'], $onHand, $bev->min_level);              
            }
            else{
                 Log::info("nigg");
            }
        });

        return ['ok' => true];
    }



    /**
     * Display the specified resource.
     */
    public function show(StockMove $stockMove)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockMove $stockMove)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockMove $stockMove)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockMove $stockMove)
    {
        //
    }
}

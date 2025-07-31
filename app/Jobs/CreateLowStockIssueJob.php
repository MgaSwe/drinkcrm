<?php

// app/Jobs/CreateLowStockIssueJob.php
namespace App\Jobs;

use App\Models\Beverage;
use App\Models\ExternalIssue;
use App\Models\StockLocation;
use App\Services\JiraClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateLowStockIssueJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(
        public int $beverageId,
        public int $stockLocationId,
        public int $currentQty,
        public int $minLevel
    ) {}

    public function handle(JiraClient $jira): void
    {
        $bev = Beverage::find($this->beverageId);
        $loc = StockLocation::find($this->stockLocationId);
        if (! $bev || ! $loc) {
            return;
        }

        // Schon ein Ticket für (Getränk×Ort) vorhanden? Dann nichts tun.
        $already = ExternalIssue::where([
            'provider'         => 'jira',
            'beverage_id'      => $bev->id,
            'stock_location_id'=> $loc->id,
        ])->exists();

        if ($already) {
            return;
        }

        $summary = sprintf('Low stock: %s @ %s', $bev->name, $loc->name);
        $desc    = sprintf(
            "Bestand: %d (min %d)\nGetränk: %s\nStandort: %s",
            $this->currentQty, $this->minLevel, $bev->name, $loc->name
        );

        // Labels & ggf. weitere Felder mitgeben
        $resp = $jira->createIssue($summary, $desc, null, null, [
            'labels' => ['drinkcrm', 'low-stock'],
        ]);

        // Issue-Key aus Antwort ziehen (array oder string)
        $key = is_array($resp) ? ($resp['key'] ?? null) : (is_string($resp) ? $resp : null);
        if ($key) {
            ExternalIssue::create([
                'provider'          => 'jira',
                'issue_key'         => $key,
                'beverage_id'       => $bev->id,
                'stock_location_id' => $loc->id,
            ]);
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalIssue extends Model
{
    protected $fillable = [
        'provider',         // 'jira'
        'issue_key',        // z. B. DRNK-123
        'beverage_id',
        'stock_location_id',
    ];
}

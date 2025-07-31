<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; 
use App\Services\JiraClient;

Route::get('/health', fn () => ['ok' => true]);

Route::post('/jira/test-issue', function (Request $req, JiraClient $jira) {
    $summary = $req->input('summary', 'DrinkCRM Test');
    $desc    = $req->input('description', 'Erstellt aus DrinkCRM');
    return $jira->createIssue($summary, $desc);
});

// Route::post('jira/add-stock', function (Request $req, JiraClient $jira){
    
// });

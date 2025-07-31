<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('external_issues', function (Blueprint $t) {
            $t->id();
            $t->string('provider');               // 'jira'
            $t->string('project_key');            // z.B. 'DRNK'
            $t->string('issue_key');              // z.B. 'DRNK-123'
            $t->foreignId('beverage_id')->constrained()->cascadeOnDelete();
            $t->foreignId('stock_location_id')->constrained()->cascadeOnDelete();
            $t->string('kind')->default('low_stock');
            $t->string('status')->default('open');  // open|closed
            $t->timestamp('last_notified_at')->nullable();
            $t->timestamps();
            $t->unique(['provider','kind','beverage_id','stock_location_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('external_issues');
    }
};

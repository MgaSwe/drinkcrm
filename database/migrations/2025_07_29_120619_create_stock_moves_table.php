<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_moves', function (Blueprint $t) {
            $t->id();

            $t->foreignId('beverage_id')->constrained()->cascadeOnDelete();

            // Bewegung: von -> nach (IN: nur 'to', OUT: nur 'from', MOVE: beides)
            $t->foreignId('from_location_id')->nullable()->constrained('stock_locations')->nullOnDelete();
            $t->foreignId('to_location_id')->nullable()->constrained('stock_locations')->nullOnDelete();

            $t->enum('type', ['IN','MOVE','OUT','ADJUSTMENT']);
            $t->integer('quantity'); // >0 (Logik enforced in App)

            // Kosten (für IN/MOVE optional)
            $t->unsignedInteger('unit_cost_cents')->nullable();

            $t->timestamp('occurred_at')->useCurrent();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('reason', 300)->nullable();

            $t->timestamps();

            $t->index(['beverage_id', 'occurred_at']);
            $t->index(['from_location_id']);
            $t->index(['to_location_id']);
            $t->index(['type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_moves');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_locations', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
            $t->enum('kind', ['WAREHOUSE','FRIDGE','OFFICE','VENDING','OTHER'])->default('WAREHOUSE');
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_locations');
    }
};

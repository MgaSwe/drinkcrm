<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('beverage_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
            $t->foreignId('parent_id')->nullable()->constrained('beverage_categories')->nullOnDelete();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beverage_categories');
    }
};

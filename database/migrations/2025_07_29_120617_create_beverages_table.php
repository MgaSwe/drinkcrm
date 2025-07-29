<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('beverages', function (Blueprint $t) {
            $t->id();

            $t->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('beverage_category_id')->nullable()->constrained()->nullOnDelete();

            $t->string('name');
            $t->string('sku')->nullable()->unique();
            $t->string('barcode')->nullable()->unique();

            $t->string('unit_label')->default('Flasche');
            $t->unsignedInteger('unit_size_ml')->default(500);

            $t->unsignedInteger('price_cents')->default(0); // VK
            $t->unsignedInteger('cost_cents')->default(0);  // Ø EK
            $t->unsignedInteger('min_level')->default(0);   // globaler Meldebestand

            $t->boolean('is_active')->default(true);
            $t->timestamps();

            $t->index(['name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beverages');
    }
};

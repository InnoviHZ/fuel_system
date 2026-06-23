<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tanks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Tank 1"
            $table->foreignId('fuel_product_id')->constrained('fuel_products')->onDelete('restrict');
            $table->decimal('capacity_litres', 10, 2);
            $table->decimal('current_stock_litres', 10, 2)->default(0);
            $table->decimal('low_stock_threshold', 10, 2);
            $table->decimal('critical_stock_threshold', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanks');
    }
};

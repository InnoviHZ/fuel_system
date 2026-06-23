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
        Schema::create('fuel_products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Premium Motor Spirit"
            $table->string('code'); // e.g., "PMS", "AGO", "DPK"
            $table->decimal('current_price', 10, 2); // Price per liter
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_products');
    }
};

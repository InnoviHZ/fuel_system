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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tank_id')->constrained('tanks');
            $table->foreignId('fuel_product_id')->constrained('fuel_products');
            $table->string('supplier_name');
            $table->string('waybill_number')->unique();
            $table->decimal('quantity_litres', 10, 2);
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->decimal('stock_before', 10, 2);
            $table->decimal('stock_after', 10, 2);
            $table->foreignId('delivered_by')->constrained('users');
            $table->date('delivery_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('tank_id');
            $table->index('delivery_date');
            $table->index('waybill_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};

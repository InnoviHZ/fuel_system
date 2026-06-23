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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('cascade');
            $table->foreignId('pump_id')->constrained('pumps')->onDelete('cascade');
            $table->foreignId('fuel_product_id')->constrained('fuel_products')->onDelete('cascade');
            $table->foreignId('attendant_id')->constrained('users')->onDelete('cascade');
            $table->decimal('quantity_litres', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['cash', 'pos', 'bank_transfer', 'credit'])->default('cash');
            $table->foreignId('credit_customer_id')->nullable()->constrained('credit_customers')->onDelete('restrict');
            $table->timestamp('sale_time');
            $table->timestamps();

            $table->index('shift_id');
            $table->index('attendant_id');
            $table->index('sale_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};

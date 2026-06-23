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
        Schema::create('credit_repayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_customer_id')->constrained('credit_customers');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'pos', 'bank_transfer']);
            $table->foreignId('received_by')->constrained('users');
            $table->date('repayment_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('credit_customer_id');
            $table->index('repayment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_repayments');
    }
};

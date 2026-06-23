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
        Schema::create('reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->unique()->constrained('shifts');
            $table->foreignId('reconciled_by')->constrained('users');
            $table->decimal('total_litres_sold', 10, 2);
            $table->decimal('expected_cash', 10, 2);
            $table->decimal('actual_cash', 10, 2);
            $table->decimal('actual_pos', 10, 2);
            $table->decimal('actual_bank_transfer', 10, 2);
            $table->decimal('total_collected', 10, 2);
            $table->decimal('cash_difference', 10, 2);
            $table->enum('status', ['balanced', 'surplus', 'shortage']);
            $table->text('supervisor_note')->nullable();
            $table->text('manager_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->index('shift_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reconciliations');
    }
};

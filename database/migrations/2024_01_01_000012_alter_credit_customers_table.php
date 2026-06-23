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
        // Modify credit_customers table to match Phase 3 requirements
        if (Schema::hasTable('credit_customers')) {
            Schema::table('credit_customers', function (Blueprint $table) {
                // Drop email column if it exists
                if (Schema::hasColumn('credit_customers', 'email')) {
                    $table->dropColumn('email');
                }
                // Rename current_credit to current_balance if current_credit exists
                if (Schema::hasColumn('credit_customers', 'current_credit') && !Schema::hasColumn('credit_customers', 'current_balance')) {
                    $table->renameColumn('current_credit', 'current_balance');
                }
                // Make phone not nullable
                if (Schema::hasColumn('credit_customers', 'phone')) {
                    $table->string('phone')->nullable(false)->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('credit_customers')) {
            Schema::table('credit_customers', function (Blueprint $table) {
                // Rename back to current_credit
                if (Schema::hasColumn('credit_customers', 'current_balance') && !Schema::hasColumn('credit_customers', 'current_credit')) {
                    $table->renameColumn('current_balance', 'current_credit');
                }
            });
        }
    }
};

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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->after('payment_method')->constrained('payment_methods')->nullOnDelete();
            $table->foreignId('submitted_to_company_id')->nullable()->after('payment_method_id')->constrained('companies')->nullOnDelete();
            $table->enum('submitted_to_scope', ['master', 'company'])->nullable()->after('submitted_to_company_id');
            $table->foreignId('verified_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->text('admin_notes')->nullable()->after('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_method_id');
            $table->dropConstrainedForeignId('submitted_to_company_id');
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn(['submitted_to_scope', 'verified_at', 'admin_notes']);
        });
    }
};

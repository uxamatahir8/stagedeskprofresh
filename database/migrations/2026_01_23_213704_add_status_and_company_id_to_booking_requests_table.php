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
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'rejected'])
                  ->default('pending')
                  ->after('user_id');
            $table->unsignedBigInteger('company_id')->nullable()->after('user_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->unsignedBigInteger('assigned_artist_id')->nullable()->after('company_id');
            $table->foreign('assigned_artist_id')->references('id')->on('artists')->onDelete('set null');
            $table->text('company_notes')->nullable()->after('additional_notes');
            $table->timestamp('confirmed_at')->nullable()->after('company_notes');
            $table->timestamp('completed_at')->nullable()->after('confirmed_at');
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['assigned_artist_id']);
            $table->dropColumn([
                'status',
                'company_id',
                'assigned_artist_id',
                'company_notes',
                'confirmed_at',
                'completed_at',
                'cancelled_at'
            ]);
        });
    }
};

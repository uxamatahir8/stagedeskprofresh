<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artist_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained('artists')->cascadeOnDelete();
            $table->foreignId('booking_request_id')->constrained('booking_requests')->cascadeOnDelete();
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->decimal('share_percentage', 5, 2)->nullable();
            $table->enum('status', ['available', 'paid_out', 'withheld'])->default('available');
            $table->timestamp('paid_out_at')->nullable();
            $table->timestamps();

            $table->index(['artist_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artist_earnings');
    }
};

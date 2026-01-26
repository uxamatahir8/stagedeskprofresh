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
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('error'); // error, warning, info, critical
            $table->string('level')->default('error'); // debug, info, warning, error, critical
            $table->text('message');
            $table->string('exception_class')->nullable();
            $table->text('exception_message')->nullable();
            $table->longText('stack_trace')->nullable();
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable(); // GET, POST, PUT, DELETE
            $table->json('request_data')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_agent')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('controller')->nullable();
            $table->string('action')->nullable();
            $table->json('context')->nullable(); // Additional context data
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('resolution_notes')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index('type');
            $table->index('level');
            $table->index('user_id');
            $table->index('created_at');
            $table->index('is_resolved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};

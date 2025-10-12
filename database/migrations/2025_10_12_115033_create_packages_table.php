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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('price');
            $table->enum('duration_type', ['weekly', 'monthly', 'yearly'])->default('monthly');
            $table->integer('duration_days');
            $table->integer('max_users_allowed')->nullable();
            $table->integer('max_requests_allowed')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('max_responses_allowed')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
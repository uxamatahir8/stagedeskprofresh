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
        Schema::create('artist_requests', function (Blueprint $table) {
            $table->id();
            // attach artist
            $table->unsignedBigInteger('artist_id');
            $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');

            // attach booking request
            $table->unsignedBigInteger('booking_requests_id');
            $table->foreign('booking_requests_id')->references('id')->on('booking_requests')->onDelete('cascade');

            // attach company
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');


            $table->datetime('assigned_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artist_requests');
    }
};
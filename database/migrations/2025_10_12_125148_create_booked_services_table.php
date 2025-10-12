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
        Schema::create('booked_services', function (Blueprint $table) {
            $table->id();
            // attach booking id
            $table->unsignedBigInteger('booking_requests_id');
            $table->foreign('booking_requests_id')->references('id')->on('booking_requests')->onDelete('cascade');

            $table->unsignedBigInteger('artist_services_id');
            $table->foreign('artist_services_id')->references('id')->on('artist_services')->onDelete('cascade');

            // attach company
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->string('total_price');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booked_services');
    }
};
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
            // attach booking request id
            $table->unsignedBigInteger('booking_requests_id');
            $table->foreign('booking_requests_id')->references('id')->on('booking_requests')->onDelete('cascade');

            $table->string('service_requested');
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
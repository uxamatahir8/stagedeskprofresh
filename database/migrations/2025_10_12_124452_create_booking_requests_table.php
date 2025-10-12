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
        Schema::create('booking_requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->comment('Customer ID from users table');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('event_name');
            $table->string('event_date');
            $table->string('event_time');
            $table->string('event_location');
            $table->string('message');

            $table->unsignedBigInteger('service_requsted')->comment('Service ID from artist_services table');
            $table->foreign('service_requsted')->references('id')->on('artist_services')->onDelete('cascade');

            $table->enum('status', ['pending', 'assigned', 'accepted', 'rejected', 'completed', 'canceled'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_requests');
    }
};
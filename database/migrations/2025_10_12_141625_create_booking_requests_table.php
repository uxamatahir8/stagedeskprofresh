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

            $table->unsignedBigInteger('user_id')->comment('Customer ID');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('event_type');


            $table->string('name');
            $table->string('surname');
            $table->date('date_of_birth');
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->json('dos')->nullable();
            $table->json('donts')->nullable();
            $table->string('playlist_spotify')->nullable();
            $table->string('additional_notes')->nullable();


            // Marriage Events Specific Fields
            $table->string('wedding_date')->nullable();
            $table->string('wedding_time')->nullable();
            $table->string('wedding_location')->nullable();
            $table->string('partner_name')->nullable();

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
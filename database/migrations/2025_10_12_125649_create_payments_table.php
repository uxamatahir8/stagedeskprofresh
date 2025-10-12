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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // attach booking id
            $table->unsignedBigInteger('booking_requests_id')->nullable();
            $table->foreign('booking_requests_id')->references('id')->on('booking_requests')->onDelete('cascade');

            // attach user id
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // attach subscription id
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->foreign('subscription_id')->references('id')->on('company_subscriptions')->onDelete('cascade');


            $table->string('amount');
            $table->string('currency');

            $table->string('transaction_id')->nullable();

            $table->string('payment_method')->nullable();

            $table->string('attachment')->nullable();

            $table->enum("type", ['booking', 'subscription'])->nullable();

            $table->enum("status", ['pending', 'completed', 'failed', 'refunded'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
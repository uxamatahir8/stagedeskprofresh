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
        Schema::create('affiliate_comissions', function (Blueprint $table) {
            $table->id();
            // attach affiliate id
            $table->unsignedBigInteger('affiliate_id');
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade');

            // attach company_id
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            // attach subscription id
            $table->unsignedBigInteger('subscription_id');
            $table->foreign('subscription_id')->references('id')->on('company_subscriptions')->onDelete('cascade');

            $table->string('amount');
            $table->enum('status', ['pending', 'paid', 'canceled']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_comissions');
    }
};
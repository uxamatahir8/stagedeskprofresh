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
        Schema::dropIfExists('artist_services');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('artist_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artist_id');
            $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
            $table->string('service_name');
            $table->string('service_description');
            $table->string('price');
            $table->string('duration');
            $table->timestamps();
        });
    }
};

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
        Schema::table('user_profiles', function (Blueprint $table) {
            // Remove city column
            $table->dropColumn('city');
            // Remove country column
            $table->dropColumn('country');

            // attach country_id
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');

            // attach state_id
            $table->unsignedBigInteger('state_id');
            $table->foreign('state_id')->references('id')->on('states');

            // attach city_id
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            // write rollback
            $table->dropColumn('country_id');
            $table->dropColumn('state_id');
            $table->dropColumn('city_id');

            $table->string('city');
            $table->string('country');
        });
    }
};
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
            //
            $table->bigInteger('country_id')->nullable()->change();
            $table->bigInteger('state_id')->nullable()->change();
            $table->bigInteger('city_id')->nullable()->change();
            $table->string('address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            //
            $table->bigInteger('country_id')->change();
            $table->bigInteger('state_id')->change();
            $table->bigInteger('city_id')->change();
            $table->bigInteger('address')->change();

        });
    }
};

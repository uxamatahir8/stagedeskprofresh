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
        //
        Schema::table("booking_requests", function (Blueprint $table) {
            // Remove Unused fields
            $table->dropColumn(['start_time', 'end_time']);

            // Add wedding related fields
            $table->text('opening_songs')->nullable()->after('event_date');
            $table->text('special_moments')->nullable()->after('opening_songs');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropColumn(['opening_songs', 'special_moments']);
            $table->time('start_time')->nullable()->after('event_date');
            $table->time('end_time')->nullable()->after('start_time');
        });
    }
};

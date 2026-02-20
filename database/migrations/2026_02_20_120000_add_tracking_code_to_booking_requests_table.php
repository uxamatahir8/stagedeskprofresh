<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->string('tracking_code', 8)->nullable()->after('id');
            $table->unique('tracking_code');
        });

        DB::table('booking_requests')
            ->whereNull('tracking_code')
            ->orderBy('id')
            ->select('id')
            ->chunkById(100, function ($bookings) {
                foreach ($bookings as $booking) {
                    do {
                        $code = strtoupper(Str::random(8));
                    } while (DB::table('booking_requests')->where('tracking_code', $code)->exists());

                    DB::table('booking_requests')
                        ->where('id', $booking->id)
                        ->update(['tracking_code' => $code]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropUnique(['tracking_code']);
            $table->dropColumn('tracking_code');
        });
    }
};

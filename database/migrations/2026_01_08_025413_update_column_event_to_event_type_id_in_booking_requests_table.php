<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            // 1. Add new nullable FK column
            $table->unsignedBigInteger('event_type_id')
                  ->nullable()
                  ->after('event_type');
        });

        // 2. Migrate existing data (event_type string -> event_type_id)
        DB::statement("
            UPDATE booking_requests br
            JOIN event_types et
                ON br.event_type = et.event_key
            SET br.event_type_id = et.id
        ");

        Schema::table('booking_requests', function (Blueprint $table) {
            // 3. Add foreign key constraint
            $table->foreign('event_type_id')
                  ->references('id')
                  ->on('event_types')
                  ->onDelete('restrict');

            // 4. Drop old column
            $table->dropColumn('event_type');
        });
    }

    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            // 1. Re-add old column
            $table->string('event_type')->nullable()->after('event_type_id');
        });

        // 2. Restore string values from event_types
        DB::statement("
            UPDATE booking_requests br
            JOIN event_types et
                ON br.event_type_id = et.id
            SET br.event_type = et.event_key
        ");

        Schema::table('booking_requests', function (Blueprint $table) {
            // 3. Drop FK and new column
            $table->dropForeign(['event_type_id']);
            $table->dropColumn('event_type_id');
        });
    }
};

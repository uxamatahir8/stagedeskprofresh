<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->decimal('share_percentage', 5, 2)->nullable()->after('bio')->comment('Artist share of booking payment (%)');
        });
    }

    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn('share_percentage');
        });
    }
};

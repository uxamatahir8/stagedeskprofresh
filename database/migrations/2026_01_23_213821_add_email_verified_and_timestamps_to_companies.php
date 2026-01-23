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
        Schema::table('companies', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable()->after('email');
            $table->boolean('is_verified')->default(false)->after('status');
            $table->timestamp('verified_at')->nullable()->after('is_verified');
            $table->timestamp('last_login_at')->nullable()->after('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['email_verified_at', 'is_verified', 'verified_at', 'last_login_at']);
        });
    }
};

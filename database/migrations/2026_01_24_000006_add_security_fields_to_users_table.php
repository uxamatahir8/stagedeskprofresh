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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('locked_until')->nullable()->after('remember_token');
            $table->integer('failed_login_attempts')->default(0)->after('locked_until');
            $table->timestamp('last_login_at')->nullable()->after('failed_login_attempts');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->timestamp('password_changed_at')->nullable()->after('last_login_ip');
            $table->boolean('force_password_change')->default(false)->after('password_changed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'locked_until',
                'failed_login_attempts',
                'last_login_at',
                'last_login_ip',
                'password_changed_at',
                'force_password_change'
            ]);
        });
    }
};

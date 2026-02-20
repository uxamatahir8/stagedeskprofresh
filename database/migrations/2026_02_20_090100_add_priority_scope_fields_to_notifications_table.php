<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'category')) {
                $table->string('category', 50)->default('general')->after('type');
            }

            if (!Schema::hasColumn('notifications', 'priority')) {
                $table->unsignedTinyInteger('priority')->default(2)->after('category');
            }

            if (!Schema::hasColumn('notifications', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable()->after('user_id');
                $table->foreign('company_id')
                    ->references('id')
                    ->on('companies')
                    ->nullOnDelete();
            }
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['user_id', 'is_read', 'created_at'], 'notifications_user_read_created_at_idx');
            $table->index(['category', 'priority', 'created_at'], 'notifications_category_priority_created_at_idx');
            $table->index(['company_id', 'created_at'], 'notifications_company_id_created_at_idx');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_user_read_created_at_idx');
            $table->dropIndex('notifications_category_priority_created_at_idx');
            $table->dropIndex('notifications_company_id_created_at_idx');

            if (Schema::hasColumn('notifications', 'company_id')) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            }
            if (Schema::hasColumn('notifications', 'priority')) {
                $table->dropColumn('priority');
            }
            if (Schema::hasColumn('notifications', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};

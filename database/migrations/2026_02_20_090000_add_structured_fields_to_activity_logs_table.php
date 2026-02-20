<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_logs', 'severity')) {
                $table->string('severity', 20)->default('info')->after('action');
            }

            if (!Schema::hasColumn('activity_logs', 'status')) {
                $table->string('status', 20)->nullable()->after('severity');
            }

            if (!Schema::hasColumn('activity_logs', 'category')) {
                $table->string('category', 50)->default('general')->after('status');
            }

            if (!Schema::hasColumn('activity_logs', 'event_key')) {
                $table->string('event_key', 191)->nullable()->after('category');
            }

            if (!Schema::hasColumn('activity_logs', 'target_type')) {
                $table->string('target_type', 191)->nullable()->after('event_key');
            }

            if (!Schema::hasColumn('activity_logs', 'target_id')) {
                $table->unsignedBigInteger('target_id')->nullable()->after('target_type');
            }

            if (!Schema::hasColumn('activity_logs', 'request_id')) {
                $table->string('request_id', 100)->nullable()->after('target_id');
            }

            if (!Schema::hasColumn('activity_logs', 'correlation_key')) {
                $table->string('correlation_key', 191)->nullable()->after('request_id');
            }
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index(['event_key', 'created_at'], 'activity_logs_event_key_created_at_idx');
            $table->index(['category', 'severity', 'created_at'], 'activity_logs_category_severity_created_at_idx');
            $table->index(['target_type', 'target_id'], 'activity_logs_target_type_target_id_idx');
            $table->index(['request_id', 'created_at'], 'activity_logs_request_id_created_at_idx');
            $table->index(['correlation_key', 'created_at'], 'activity_logs_correlation_key_created_at_idx');
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('activity_logs_event_key_created_at_idx');
            $table->dropIndex('activity_logs_category_severity_created_at_idx');
            $table->dropIndex('activity_logs_target_type_target_id_idx');
            $table->dropIndex('activity_logs_request_id_created_at_idx');
            $table->dropIndex('activity_logs_correlation_key_created_at_idx');

            if (Schema::hasColumn('activity_logs', 'correlation_key')) {
                $table->dropColumn('correlation_key');
            }
            if (Schema::hasColumn('activity_logs', 'request_id')) {
                $table->dropColumn('request_id');
            }
            if (Schema::hasColumn('activity_logs', 'target_id')) {
                $table->dropColumn('target_id');
            }
            if (Schema::hasColumn('activity_logs', 'target_type')) {
                $table->dropColumn('target_type');
            }
            if (Schema::hasColumn('activity_logs', 'event_key')) {
                $table->dropColumn('event_key');
            }
            if (Schema::hasColumn('activity_logs', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('activity_logs', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('activity_logs', 'severity')) {
                $table->dropColumn('severity');
            }
        });
    }
};

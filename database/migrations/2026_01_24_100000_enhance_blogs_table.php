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
        Schema::table('blogs', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('content');
            $table->integer('views_count')->default(0)->after('published_at');
            $table->integer('reading_time')->nullable()->after('views_count');
            $table->boolean('is_featured')->default(false)->after('reading_time');
            $table->string('meta_title')->nullable()->after('is_featured');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->json('tags')->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['excerpt', 'views_count', 'reading_time', 'is_featured', 'meta_title', 'meta_description', 'tags']);
        });
    }
};

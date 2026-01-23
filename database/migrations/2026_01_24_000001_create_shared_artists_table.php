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
        Schema::create('shared_artists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artist_id');
            $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');

            $table->unsignedBigInteger('owner_company_id')->comment('Company that owns the artist');
            $table->foreign('owner_company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->unsignedBigInteger('shared_with_company_id')->comment('Company the artist is shared with');
            $table->foreign('shared_with_company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->enum('status', ['pending', 'accepted', 'rejected', 'revoked'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('shared_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            // Prevent duplicate shares
            $table->unique(['artist_id', 'shared_with_company_id'], 'unique_artist_share');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shared_artists');
    }
};

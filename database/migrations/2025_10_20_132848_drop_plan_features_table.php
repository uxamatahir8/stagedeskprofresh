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
        // drop table plan_features
        Schema::dropIfExists('plan_features');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //

    }
};
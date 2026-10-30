<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('technical_description_sync_runs', function (Blueprint $table) {
            $table->string('filter_type', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('technical_description_sync_runs', function (Blueprint $table) {
            $table->string('filter_type', 20)->change();
        });
    }
};

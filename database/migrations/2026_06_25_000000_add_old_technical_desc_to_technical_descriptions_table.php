<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('technical_descriptions', function (Blueprint $table) {
            $table->longText('old_technical_desc')->nullable()->after('vsr');
        });
    }

    public function down(): void
    {
        Schema::table('technical_descriptions', function (Blueprint $table) {
            $table->dropColumn('old_technical_desc');
        });
    }
};

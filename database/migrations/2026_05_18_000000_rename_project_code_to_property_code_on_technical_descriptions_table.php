<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('technical_descriptions', 'project_code')) {
            return;
        }

        Schema::table('technical_descriptions', function (Blueprint $table) {
            $table->renameColumn('project_code', 'property_code');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('technical_descriptions', 'property_code')) {
            return;
        }

        Schema::table('technical_descriptions', function (Blueprint $table) {
            $table->renameColumn('property_code', 'project_code');
        });
    }
};

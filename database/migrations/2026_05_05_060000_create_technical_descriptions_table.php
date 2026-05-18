<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_descriptions', function (Blueprint $table) {
            $table->id();
            $table->string('property_code')->unique();
            $table->string('company_name');
            $table->string('registry_of_deeds');
            $table->string('tct');
            $table->string('vsr');
            $table->longText('technical_description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_descriptions');
    }
};

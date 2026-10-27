<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_description_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sync_run_id')
                ->nullable()
                ->constrained('technical_description_sync_runs')
                ->cascadeOnDelete();
            $table->foreignId('technical_description_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('property_code')->index();
            $table->string('action', 20);
            $table->string('status', 20)->default('completed')->index();
            $table->json('changes');
            $table->text('error_message')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
          
            $table->unique(['sync_run_id', 'property_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_description_sync_logs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_description_sync_runs', function (Blueprint $table) {
            $table->id();
            $table->uuid('sync_request_id')->unique();
            $table->string('filter_type', 20);
            $table->string('filter_value')->index();
            $table->string('status', 30)->default('queued')->index();
            $table->unsignedInteger('total_count')->default(0);
            $table->unsignedInteger('processed_count')->default(0);
            $table->unsignedInteger('created_count')->default(0);
            $table->unsignedInteger('updated_count')->default(0);
            $table->unsignedInteger('unchanged_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->text('error_message')->nullable();
            $table->json('summary')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->unsignedInteger('reported_success_count')->nullable();
            $table->unsignedInteger('reported_failed_count')->nullable();
            $table->boolean('result_reconciled')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_description_sync_runs');
    }
};

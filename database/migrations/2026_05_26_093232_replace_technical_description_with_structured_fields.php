<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('technical_descriptions', function (Blueprint $table) {
            $table->string('survey_plan_no')->nullable()->after('vsr');
            $table->string('block_no')->nullable()->after('survey_plan_no');
            $table->string('lot_no')->nullable()->after('block_no');
            $table->text('portion_of_lot')->nullable()->after('lot_no');
            $table->string('lrc_record_no')->nullable()->after('portion_of_lot');
            $table->string('land_owner_claimant')->nullable()->after('lrc_record_no');
            $table->text('location')->nullable()->after('land_owner_claimant');
            $table->string('area')->nullable()->after('location');
            $table->text('description_of_corners')->nullable()->after('area');
            $table->boolean('bearings')->default(false)->after('description_of_corners');
            $table->string('original_date_of_survey')->nullable()->after('bearings');
            $table->string('date_of_survey')->nullable()->after('original_date_of_survey');
            $table->string('date_approved')->nullable()->after('date_of_survey');
            $table->string('geodetic_engineer')->nullable()->after('date_approved');
        });

        Schema::table('technical_descriptions', function (Blueprint $table) {
            if (Schema::hasColumn('technical_descriptions', 'technical_description')) {
                $table->dropColumn('technical_description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('technical_descriptions', function (Blueprint $table) {
            $table->longText('technical_description')->nullable()->after('vsr');
        });

        Schema::table('technical_descriptions', function (Blueprint $table) {
            $table->dropColumn([
                'survey_plan_no',
                'block_no',
                'lot_no',
                'portion_of_lot',
                'lrc_record_no',
                'land_owner_claimant',
                'location',
                'area',
                'description_of_corners',
                'bearings',
                'original_date_of_survey',
                'date_of_survey',
                'date_approved',
                'geodetic_engineer',
            ]);
        });
    }
};

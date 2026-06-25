<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalDescription extends Model
{
    protected $fillable = [
        'property_code',
        'company_name',
        'registry_of_deeds',
        'tct',
        'vsr',
        'old_technical_desc',
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
    ];

    protected $casts = [
        'bearings' => 'boolean',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_code', 'code');
    }
}

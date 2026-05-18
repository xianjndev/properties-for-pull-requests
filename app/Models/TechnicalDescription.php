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
        'technical_description',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_code', 'code');
    }
}

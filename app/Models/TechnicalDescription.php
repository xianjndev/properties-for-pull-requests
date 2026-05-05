<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalDescription extends Model
{
    protected $fillable = [
        'project_code',
        'company_name',
        'registry_of_deeds',
        'tct',
        'vsr',
        'technical_description',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_code', 'code');
    }
}

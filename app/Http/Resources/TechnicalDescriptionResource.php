<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TechnicalDescriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'property_code' => $this->property_code,
            'company_name' => $this->company_name,
            'registry_of_deeds' => $this->registry_of_deeds,
            'tct' => $this->tct,
            'vsr' => $this->vsr,
            'old_technical_desc' => $this->old_technical_desc,
            'survey_plan_no' => $this->survey_plan_no,
            'block_no' => $this->block_no,
            'lot_no' => $this->lot_no,
            'portion_of_lot' => $this->portion_of_lot,
            'lrc_record_no' => $this->lrc_record_no,
            'land_owner_claimant' => $this->land_owner_claimant,
            'location' => $this->location,
            'area' => $this->area,
            'description_of_corners' => $this->description_of_corners,
            'bearings' => $this->bearings,
            'original_date_of_survey' => $this->original_date_of_survey,
            'date_of_survey' => $this->date_of_survey,
            'date_approved' => $this->date_approved,
            'geodetic_engineer' => $this->geodetic_engineer,
        ];
    }
}

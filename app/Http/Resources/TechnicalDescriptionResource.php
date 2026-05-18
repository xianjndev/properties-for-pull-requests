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
            'technical_description' => $this->technical_description,
        ];
    }
}

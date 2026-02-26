<?php

namespace Homeful\Properties\Traits;

use Homeful\Properties\Models\Property;

trait HasAdditionalPropertyAttributes
{
    const PHASE_FIELD = 'phase';
    const BLOCK_FIELD = 'block';
    const LOT_FIELD = 'lot';
    const BUILDING_FIELD = 'building';
    const FLOOR_AREA_FIELD = 'floor_area';
    const LOT_AREA_FIELD = 'lot_area';
    const UNIT_TYPE_FIELD = 'unit_type';
    const UNIT_TYPE_INTERIOR_FIELD = 'unit_type_interior';
    const HOUSE_COLOR_FIELD = 'house_color';
    const ROOF_STYLE_FIELD = 'roof_style';
    const END_UNIT_FIELD = 'end_unit';
    const VERANDA_FIELD = 'veranda';
    const BALCONY_FIELD = 'balcony';
    const FIREWALL_FIELD = 'firewall';
    const EAVES_FIELD = 'eaves';
    const BEDROOMS_FIELD = 'bathrooms';
    const TOILETS_AND_BATHROOMS_FIELD = 'toilets_and_bathrooms';
    const PARKING_SLOTS_FIELD = 'parking_slots';
    const CARPORTS_FIELD = 'carports';
    const PROJECT_CODE = 'project_code';
    /* @deprecated */
    const PROJECT_LOCATION = 'project_location';
    /* @deprecated */
    const PROJECT_ADDRESS = 'project_address';
    const TCP = 'tcp';

    public function initializeHasAdditionalAttributes(): void
    {
        $this->mergeFillable([
            'phase',
            'block',
            'lot',
            'building',
            'floor_area',
            'lot_area',
            'unit_type',
            'unit_type_interior',
            'house_color',
            'roof_style',
            'end_unit',
            'veranda',
            'balcony',
            'firewall',
            'eaves',
            'bathrooms',
            'toilets_and_bathrooms',
            'parking_slots',
            'carports',
            'project_code',
            'tcp',
            'project_description',
            'digital_assets',
        ]);

        $this->appends = array_merge($this->appends, [
            'phase',
            'block',
            'lot',
            'building',
            'floor_area',
            'lot_area',
            'unit_type',
            'unit_type_interior',
            'house_color',
            'roof_style',
            'end_unit',
            'veranda',
            'balcony',
            'firewall',
            'eaves',
            'bathrooms',
            'toilets_and_bathrooms',
            'parking_slots',
            'carports',
            'project_code',
            'tcp',
            'project_description',
            'digital_assets',
        ]);
    }

    public function setPhaseAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::PHASE_FIELD, $value);
        return $this;
    }

    public function getPhaseAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Property::PHASE_FIELD) ?? '';
    }

    public function setBlockAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::BLOCK_FIELD, $value);
        return $this;
    }

    public function getBlockAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Property::BLOCK_FIELD) ?? '';
    }

    public function setLotAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::LOT_FIELD, $value);
        return $this;
    }

    public function getLotAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Property::LOT_FIELD) ?? '';
    }

    public function setBuildingAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::BUILDING_FIELD, $value);
        return $this;
    }

    public function getBuildingAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Property::BUILDING_FIELD) ?? '';
    }

    public function setFloorAreaAttribute(?float $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::FLOOR_AREA_FIELD, $value);
        return $this;
    }

    public function getFloorAreaAttribute(): ?float
    {
        return $this->getAttribute('meta')->get(Property::FLOOR_AREA_FIELD) ?? 0;
    }

    public function setLotAreaAttribute(?float $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::LOT_AREA_FIELD, $value);
        return $this;
    }

    public function getLotAreaAttribute(): ?float
    {
        return $this->getAttribute('meta')->get(Property::LOT_AREA_FIELD) ?? 0;
    }

    public function setUnitTypeAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::UNIT_TYPE_FIELD, $value);
        return $this;
    }

    public function getUnitTypeAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Property::UNIT_TYPE_FIELD) ?? '';
    }

    public function setUnitTypeInteriorAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::UNIT_TYPE_INTERIOR_FIELD, $value);
        return $this;
    }

    public function getUnitTypeInteriorAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Property::UNIT_TYPE_INTERIOR_FIELD) ?? '';
    }

    public function setHouseColorAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::HOUSE_COLOR_FIELD, $value);
        return $this;
    }

    public function getHouseColorAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Property::HOUSE_COLOR_FIELD) ?? '';
    }

    public function setRoofStyleAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::ROOF_STYLE_FIELD, $value);
        return $this;
    }

    public function getRoofStyleAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Property::ROOF_STYLE_FIELD) ?? '';
    }

    public function setEndUnitAttribute(?bool $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::END_UNIT_FIELD, $value);
        return $this;
    }

    public function getEndUnitAttribute(): ?bool
    {
        return $this->getAttribute('meta')->get(Property::END_UNIT_FIELD) ?? false;
    }
    public function setVerandaAttribute(?bool $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::VERANDA_FIELD, $value);
        return $this;
    }

    public function getVerandaAttribute(): ?bool
    {
        return $this->getAttribute('meta')->get(Property::VERANDA_FIELD) ?? false;
    }

    public function setBalconyAttribute(?bool $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::BALCONY_FIELD, $value);
        return $this;
    }

    public function getBalconyAttribute(): ?bool
    {
        return $this->getAttribute('meta')->get(Property::BALCONY_FIELD) ?? false;
    }

    public function setFirewallAttribute(?bool $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::FIREWALL_FIELD, $value);
        return $this;
    }

    public function getFirewallAttribute(): ?bool
    {
        return $this->getAttribute('meta')->get(Property::FIREWALL_FIELD) ?? false;
    }

    public function setEavesAttribute(?bool $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::EAVES_FIELD, $value);
        return $this;
    }

    public function getEavesAttribute(): ?bool
    {
        return $this->getAttribute('meta')->get(Property::EAVES_FIELD) ?? false;
    }

    public function setBedroomsAttribute(?int $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::BEDROOMS_FIELD, $value);
        return $this;
    }

    public function getBedroomsAttribute(): ?int
    {
        return $this->getAttribute('meta')->get(Property::BEDROOMS_FIELD) ?? 0;
    }

    public function setToiletsAndBathroomsAttribute(?int $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::TOILETS_AND_BATHROOMS_FIELD, $value);
        return $this;
    }

    public function getToiletsAndBathroomsAttribute(): ?int
    {
        return $this->getAttribute('meta')->get(Property::TOILETS_AND_BATHROOMS_FIELD) ?? 0;
    }

    public function setParkingSlotsAttribute(?int $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::PARKING_SLOTS_FIELD, $value);
        return $this;
    }

    public function getParkingSlotsAttribute(): ?int
    {
        return $this->getAttribute('meta')->get(Property::PARKING_SLOTS_FIELD) ?? 0;
    }

    public function setCarportsAttribute(?int $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::CARPORTS_FIELD, $value);
        return $this;
    }

    public function getCarportsAttribute(): ?int
    {
        return $this->getAttribute('meta')->get(Property::CARPORTS_FIELD) ?? 0;
    }

    public function setProjectCodeAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::PROJECT_CODE, $value);
        return $this;
    }

    public function getProjectCodeAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Property::PROJECT_CODE) ?? '';
    }

    /* @deprecated */
    public function setProjectLocationAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::PROJECT_LOCATION, $value);
        return $this;
    }

    /* @deprecated */
    public function getProjectLocationAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Property::PROJECT_LOCATION) ?? '';
    }

    /* @deprecated */
    public function setProjectAddressAttribute(?string $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::PROJECT_ADDRESS, $value);
        return $this;
    }

    /* @deprecated */
    public function getProjectAddressAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Property::PROJECT_ADDRESS) ?? '';
    }

    public function setTCPAttribute(?float $value): self
    {
        if ($value === null) {
            return $this;
        }
        $this->getAttribute('meta')->set(Property::TCP, $value);
        return $this;
    }

    public function getTCPAttribute(): ?float
    {
        return $this->getAttribute('meta')->get(Property::TCP) ?? 0;
    }
}

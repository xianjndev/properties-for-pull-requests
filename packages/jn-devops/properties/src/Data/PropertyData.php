<?php

namespace Homeful\Properties\Data;

use Homeful\Properties\Data\ProjectData;
use Homeful\Properties\Models\Property;
use Homeful\Products\Data\ProductData;
use Spatie\LaravelData\Data;

class PropertyData extends Data
{
    public function __construct(
        public string $code,
        public string $name,
        public string $type,
        public string $cluster,
        public string $phase,
        public string $block,
        public string $lot,
        public string $building,
        public float $floor_area,
        public float $lot_area,
        public string $unit_type,
        public string $unit_type_interior,
        public string $house_color,
        public string $roof_style,
        public bool $end_unit,
        public bool $veranda,
        public bool $balcony,
        public bool $firewall,
        public bool $eaves,
        public int $bedrooms,
        public int $toilets_and_bathrooms,
        public int $parking_slots,
        public int $carports,
        public string $project_code,
        public string $project_location,
        public string $project_address,
        public string $sku,
        public float $tcp,
        public ?ProductData $product,
        public ?ProjectData $project
    ) {}

    public static function fromModel(Property $property): PropertyData
    {
        return new self(
            code: $property->code,
            name: $property->name,
            type: $property->type,
            cluster: $property->cluster,
            phase: $property->phase,
            block: $property->block,
            lot: $property->lot,
            building: $property->building,
            floor_area: $property->floor_area,
            lot_area: $property->lot_area,
            unit_type: $property->unit_type,
            unit_type_interior: $property->unit_type_interior,
            house_color: $property->house_color,
            roof_style: $property->roof_style,
            end_unit: $property->end_unit,
            veranda: $property->veranda,
            balcony: $property->balcony,
            firewall: $property->firewall,
            eaves: $property->eaves,
            bedrooms: $property->bedrooms,
            toilets_and_bathrooms: $property->toilets_and_bathrooms,
            parking_slots: $property->parking_slots,
            carports: $property->carports,
            project_code: $property->project_code,
            project_location: $property->project_location,
            project_address: $property->project_address,
            sku: $property->sku,
            tcp: $property->tcp,
            product: $property->product == null ? null : ProductData::fromModel($property->product),
            project: $property->project == null ? null : ProjectData::fromModel($property->project)
        );
    }
}

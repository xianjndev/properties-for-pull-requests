<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use App\Filament\Exports\PropertyExporter;
use App\Filament\Imports\PropertyImporter;
use App\Filament\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Homeful\Properties\Models\Property;


class ManageProperties extends ManageRecords
{
    protected static string $resource = PropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function (array $data, string $model): Property {
                    $property = Property::create([
                        'code' => $data['code'],
                        'name' => $data['name'],
                        'sku' => $data['sku'],
                        'type' => $data['type'],
                        'cluster' => $data['cluster'],
                        'status' => $data['status'],
                    ]);
                    $property->meta->set('tcp', $data['tcp']);
                    $property->meta->set('unit_type_interior', $data['unit_type_interior']);
                    $property->meta->set('phase', $data['phase']);
                    $property->meta->set('block', $data['block']);
                    $property->meta->set('lot', $data['lot']);
                    $property->meta->set('floor_area', $data['floor_area']);
                    $property->meta->set('lot_area', $data['lot_area']);
                    $property->meta->set('unit_type', $data['unit_type']);
                    $property->meta->set('project_code', $data['project_code']);
                    $property->meta->set('project_location', $data['project_location']);
                    $property->meta->set('project_address', $data['project_address']);
                    $property->meta->set('bathrooms', $data['bathrooms']);
                    $property->meta->set('toilets_and_bathrooms', $data['toilets_and_bathrooms']);
                    $property->meta->set('parking_slots', $data['parking_slots']);
                    $property->meta->set('carports', $data['carports']);
                    $property->meta->set('project_description', $data['project_description']);
                    $property->meta->set('digital_assets', $data['digital_assets']);
                    
                    $property->save();

                    return $property;
                }),
            Actions\ImportAction::make()
                ->hidden(auth()->user()?->cannot('import_property'))
                ->importer(PropertyImporter::class),
            Actions\ExportAction::make()
                ->hidden(auth()->user()?->cannot('export_property'))
                ->exporter(PropertyExporter::class),
        ];
    }
}

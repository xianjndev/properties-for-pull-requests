<?php

namespace App\Filament\Imports;

use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Homeful\Products\Models\Product;
use Homeful\Properties\Models\Property;

class ProductsImportImporter extends Importer
{
    protected static ?string $model = Property::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->guess(['property_code'])
                ->rules(['max:255']),
            ImportColumn::make('name')
                ->guess(['property_name'])
                ->rules(['max:255']),
            ImportColumn::make('type')
                ->guess(['property_type'])
                ->rules(['max:255']),
            ImportColumn::make('cluster')
                ->rules(['max:255']),
            ImportColumn::make('sku')
                ->label('SKU')
                ->rules(['max:255']),
            ImportColumn::make('brand')
                ->rules(['max:255']),
            ImportColumn::make('category')
                ->rules(['max:255']),
//            ImportColumn::make('market_segment')
//                ->label('Market Segement')
//                ->rules(['max:255']),
            ImportColumn::make('phase')
                ->rules(['max:255']),
            ImportColumn::make('block')
                ->rules(['max:255']),
            ImportColumn::make('lot')
                ->rules(['max:255']),
            ImportColumn::make('floor_area')
                ->numeric(),
            ImportColumn::make('lot_area')
                ->numeric(),
            // ImportColumn::make('building')
            //     ->rules(['max:255']),
            ImportColumn::make('unit_type')
                ->rules(['max:255']),
            ImportColumn::make('unit_type_interior')
                ->ignoreBlankState()
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('house_color')
                ->ignoreBlankState()
                ->rules(['nullable', 'string', 'max:255']),
            ImportColumn::make('bedrooms')
                ->integer()
                ->ignoreBlankState(),
            ImportColumn::make('parking_slots')
                ->guess(['parking'])
                ->integer()
                ->ignoreBlankState(),
            ImportColumn::make('toilets_and_bathrooms')
                ->guess(['toilet_and_bathrooms'])
                ->integer()
                ->ignoreBlankState(),
            ImportColumn::make('carports')
                ->integer()
                ->ignoreBlankState(),
            ImportColumn::make('project_code')
                ->rules(['max:255']),
            ImportColumn::make('project_location')
                ->rules(['max:255']),
            ImportColumn::make('project_address'),

            ImportColumn::make('price')
                ->numeric(),
            ImportColumn::make('processing_fee')
                ->numeric(),
            ImportColumn::make('tcp')
                ->numeric(),
            ImportColumn::make('percent_dp')
                ->numeric(),
            ImportColumn::make('dp_term')
                ->numeric(),
            ImportColumn::make('percent_mf')
                ->numeric(),
            ImportColumn::make('status_code'),
            ImportColumn::make('key_location'),
            ImportColumn::make('destinations'),
            ImportColumn::make('directions'),
            ImportColumn::make('amenities'),
            ImportColumn::make('facade_url'),
            ImportColumn::make('description'),
            ImportColumn::make('digital_assets'),
        ];
    }

    public function resolveRecord(): ?Property
    {
        $facadeUrl = $this->data['facade_url'] ?? null;
        // dd($this->data);

//        if ($facadeUrl) {
//            // Decode the JSON string into an associative array
//            $facadeData = json_decode($facadeUrl, true);
//
//            // Check if the 'facade' key exists and get its value
//            $facade = $facadeData['facade'] ?? null;
//        }


        // Create or update the Product record based on SKU
        $product = Product::updateOrCreate(
            ['sku' => (string) ($this->data['sku'] ?? '')],
            [
                'name' => (string) ($this->data['name'] ?? ''),
                'brand' => (string) ($this->data['brand'] ?? ''),
                'category' => (string) ($this->data['category'] ?? ''),
                'description' => (string) ($this->data['description'] ?? ''),
                'price' => (float) ($this->data['price'] ?? 0),
                // 'location' => (string) ($this->data['location'] ?? ''),
                'directions' => (string) ($this->data['directions'] ?? ''),
                'amenities' => (string) ($this->data['amenities'] ?? ''),
                'facade_url' => (string) ($facadeUrl ?? ''),
                'destinations' => (string) ($this->data['destinations'] ?? ''),
            ]
        );
//
//        $product->meta->set('percent_down_payment',$this->data['percent_dp']);
//        $product->meta->set('percent_miscellaneous_fees',$this->data['percent_mf']);
//        $product->meta->set('down_payment_term',$this->data['dp_term']);
//        $product->save();

        $product->facade_url= $facadeUrl ?? '';
        $product->processing_fee = (float) ($this->data['processing_fee'] ?? 0);
        $product->status_code= $this->data['status_code'] ?? '';
        $product->destinations= $this->data['destinations'] ?? '';
        $product->directions = $this->data['directions'] ?? '';
        $product->amenities= $this->data['amenities'] ?? '';
        $product->key_location= $this->data['key_location'] ?? '';
        $product->percent_down_payment=(float) ($this->data['percent_dp'] ?? 0);
        $product->down_payment_term=(float) ($this->data['dp_term'] ?? 0);
        $product->percent_miscellaneous_fees=(float) ($this->data['percent_mf'] ?? 0);
        $product->digital_assets=(string) ($this->data['digital_assets'] ?? '');
        $product->save();

//        dd($product);

        // Create or update the Property record based on SKU
        $property = Property::updateOrCreate(
            ['code' => (string) ($this->data['code'] ?? '')],
            [
                'sku' => (string) ($this->data['sku'] ?? ''),
                'name' => (string) ($this->data['name'] ?? ''),
                'type' => (string) ($this->data['type'] ?? ''),
                'cluster' => (string) ($this->data['cluster'] ?? 0),
                'phase' => (string) ($this->data['phase'] ?? ''),
                'block' => (string) ($this->data['block'] ?? ''),
                'lot' => (string) ($this->data['lot'] ?? ''),
                'floor_area' => (float) ($this->data['floor_area'] ?? 0),
                'lot_area' => (float) ($this->data['lot_area'] ?? 0),
                'unit_type' => (string) ($this->data['unit_type'] ?? ''),
                'project_code' => (string) ($this->data['project_code'] ?? ''),
                'project_location' => (string) ($this->data['project_location'] ?? ''),
                'project_address' => (string) ($this->data['project_address'] ?? ''),
                'tcp' => (float) ($this->data['tcp'] ?? 0),
            ]
        );
        if (filled($this->data['unit_type_interior'] ?? null)) {
            $property->unit_type_interior = $this->data['unit_type_interior'];
        }

        if (filled($this->data['house_color'] ?? null)) {
            $property->house_color = $this->data['house_color'];
        }

        if (filled($this->data['bedrooms'] ?? null)) {
            $property->bedrooms = $this->data['bedrooms'];
        }

        if (filled($this->data['parking_slots'] ?? null)) {
            $property->parking_slots = $this->data['parking_slots'];
        }

        if (filled($this->data['toilets_and_bathrooms'] ?? null)) {
            $property->toilets_and_bathrooms = $this->data['toilets_and_bathrooms'];
        }

        if (filled($this->data['carports'] ?? null)) {
            $property->carports = $this->data['carports'];
        }

        $project = $property->project;
        if ($project) {
            $project->project_description = (string) ($this->data['description'] ?? '');
            $project->save();
        }

        $property->save();

        return $property;
    }

    protected function beforeSave(): void
    {
        $facadeUrl = $this->data['facade_url'] ?? null;
        $product = Product::updateOrCreate(
            ['sku' => (string) ($this->data['sku'] ?? '')],
            [
                'name' => (string) ($this->data['name'] ?? ''),
                'brand' => (string) ($this->data['brand'] ?? ''),
                'category' => (string) ($this->data['category'] ?? ''),
                'description' => (string) ($this->data['description'] ?? ''),
                'price' => (float) ($this->data['price'] ?? 0),
                // 'location' => (string) ($this->data['location'] ?? ''),
                'directions' => (string) ($this->data['directions'] ?? ''),
                'amenities' => (string) ($this->data['amenities'] ?? ''),
                'facade_url' => (string) ($facadeUrl ?? ''),
                'destinations' => (string) ($this->data['destinations'] ?? ''),
            ]
        );
        $product->facade_url= $facadeUrl ?? '';
        $product->processing_fee = (float) ($this->data['processing_fee'] ?? 0);
        $product->status_code= $this->data['status_code'] ?? '';
        $product->destinations= $this->data['destinations'] ?? '';
        $product->directions = $this->data['directions'] ?? '';
        $product->amenities= $this->data['amenities'] ?? '';
        $product->key_location= $this->data['key_location'] ?? '';
        $product->percent_down_payment=(float) ($this->data['percent_dp'] ?? 0);
        $product->down_payment_term=(float) ($this->data['dp_term'] ?? 0);
        $product->percent_miscellaneous_fees=(float) ($this->data['percent_mf'] ?? 0);
        $product->digital_assets=(string) ($this->data['digital_assets'] ?? '');
        $product->save();

        $this->record = Property::updateOrCreate(
            ['code' => (string) ($this->data['code'] ?? '')],
            [
                'sku' => (string) ($this->data['sku'] ?? ''),
                'name' => (string) ($this->data['name'] ?? ''),
                'type' => (string) ($this->data['type'] ?? ''),
                'cluster' => (string) ($this->data['cluster'] ?? 0),
                'phase' => (string) ($this->data['phase'] ?? ''),
                'block' => (string) ($this->data['block'] ?? ''),
                'lot' => (string) ($this->data['lot'] ?? ''),
                'floor_area' => (float) ($this->data['floor_area'] ?? 0),
                'lot_area' => (float) ($this->data['lot_area'] ?? 0),
                'unit_type' => (string) ($this->data['unit_type'] ?? ''),
                'project_code' => (string) ($this->data['project_code'] ?? ''),
                'project_location' => (string) ($this->data['project_location'] ?? ''),
                'project_address' => (string) ($this->data['project_address'] ?? ''),
                'tcp' => (float) ($this->data['tcp'] ?? 0),
            ]
        );

        $this->record->phase=(string) ($this->data['phase'] ?? '');
        $this->record->block=(string) ($this->data['block'] ?? '');
        $this->record->lot=(string) ($this->data['lot'] ?? '');

        $this->record->floor_area=(float) ($this->data['floor_area'] ?? 0);
        $this->record->lot_area=(float) ($this->data['lot_area'] ?? 0);

        $this->record->unit_type=(string) ($this->data['unit_type'] ?? '');
        $this->record->project_code=(string) ($this->data['project_code'] ?? '');
        $this->record->project_location=(string) ($this->data['project_location'] ?? '');
        $this->record->project_address=(string) ($this->data['project_address'] ?? '');

        if (filled($this->data['house_color'] ?? null)) {
            $this->record->house_color = $this->data['house_color'];
        }

        if (filled($this->data['bedrooms'] ?? null)) {
            $this->record->bedrooms = $this->data['bedrooms'];
        }

        if (filled($this->data['toilets_and_bathrooms'] ?? null)) {
            $this->record->toilets_and_bathrooms = $this->data['toilets_and_bathrooms'];
        }

        if (filled($this->data['parking_slots'] ?? null)) {
            $this->record->parking_slots = $this->data['parking_slots'];
        }

        if (filled($this->data['carports'] ?? null)) {
            $this->record->carports = $this->data['carports'];
        }

        if (filled($this->data['unit_type_interior'] ?? null)) {
            $this->record->unit_type_interior = $this->data['unit_type_interior'];
        }
        $this->record->product()->associate($product);
        $project = $this->record->project;
        if ($project) {
            $project->project_description = (string) ($this->data['description'] ?? '');
            $project->save();
        }
        $this->record->save();
        // Runs before a record is saved to the database.
    }


    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your property import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}

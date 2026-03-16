<?php

namespace App\Filament\Imports;

use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Homeful\Products\Models\Product;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('sku'),
            ImportColumn::make('project_code'),
            ImportColumn::make('name'),
            ImportColumn::make('brand'),
            ImportColumn::make('hdmf'),
            ImportColumn::make('bank'),
            ImportColumn::make('preferred_option'),
            ImportColumn::make('reservation_fee'),
            ImportColumn::make('category'),
            ImportColumn::make('description'),
            ImportColumn::make('price')
                ->guess(['typical_price','price']),
            ImportColumn::make('destinations'),
            ImportColumn::make('directions'),
            ImportColumn::make('amenities'),
            ImportColumn::make('facade_url'),
            ImportColumn::make('lot_area'),
            ImportColumn::make('floor_area'),
            ImportColumn::make('property_type'),
            ImportColumn::make('house_type'),
            ImportColumn::make('unit_type'),
            ImportColumn::make('appraised_value'),
            ImportColumn::make('percent_dp'),
            ImportColumn::make('dp_term'),
            ImportColumn::make('percent_mf'),
            ImportColumn::make('key_location'),
            ImportColumn::make('digital_assets'),
            ImportColumn::make('percent_gmi'),
            ImportColumn::make('max_age'),
            ImportColumn::make('balance_payment_interest_rate')
                ->guess(['interest_rate','bp_interest_rate']),
            ImportColumn::make('mrif'),
            ImportColumn::make('income_requirement_multiplier')
            ->guess(['income_requirement_multiplier']),
            ImportColumn::make('maximum_paying_age'),
            ImportColumn::make('balance_payment_term')
                ->guess(['bp_term']),
            ImportColumn::make('processing_fee'),
            ImportColumn::make('phased_out'),
        ];
    }


    public function resolveRecord(): ?Product
    {
        $facadeUrl = $this->data['facade_url'] ?? null;
        $product = Product::firstOrNew(
            ['sku' => (string) ($this->data['sku'] ?? '')],
            [
                'name' => (string) ($this->data['name'] ?? ''),
                'brand' => (string) ($this->data['brand'] ?? ''),
                'category' => (string) ($this->data['category'] ?? ''),
                'bank' => (bool) ($this->data['bank'] ?? false),
                'hdmf' => (bool) ($this->data['hdmf'] ?? false),
                'preferred_option' => (string) ($this->data['preferred_option'] ?? ''),
                'description' => (string) ($this->data['description'] ?? ''),
                'price' => (float) ($this->data['typical_price'] ?? 0),
                'reservation_fee' => (float) ($this->data['reservation_fee'] ?? 0),
                'directions' => (string) ($this->data['directions'] ?? ''),
                'amenities' => (string) ($this->data['amenities'] ?? ''),
                'facade_url' => (string) ($facadeUrl ?? ''),
                'destinations' => (string) ($this->data['destinations'] ?? ''),
            ]
        );
        $product->facade_url= $facadeUrl ?? '';
        $product->directions= $this->data['directions'] ?? '';
        $product->name= $this->data['name'] ?? '';
        $product->brand= $this->data['brand'] ?? '';
        $product->bank= $this->data['bank'] ?? false;
        $product->hdmf= $this->data['hdmf'] ?? false;
        $product->preferred_option= $this->data['preferred_option'] ?? '';
        $product->category= $this->data['category'] ?? '';
        $product->description= $this->data['description'] ?? '';
        $product->destinations= $this->data['destinations'] ?? '';
        $product->amenities= $this->data['amenities'] ?? '';

        $product->key_location= $this->data['key_location'] ?? '';
        $product->percent_down_payment=(float) ($this->data['percent_dp'] ?? 0);
        $product->down_payment_term=(float) ($this->data['dp_term'] ?? 0);
        $product->percent_miscellaneous_fees=(float) ($this->data['percent_mf'] ?? 0);
        $product->digital_assets=(string) ($this->data['digital_assets'] ?? '');

        $product->percent_gross_monthly_income = (float) ($this->data['percent_gmi'] ?? 0);
        $product->balance_payment_interest_rate = (float) ($this->data['balance_payment_interest_rate'] ?? 0);
        $product->max_age = (float) ($this->data['max_age'] ?? 0);
        $product->mortgage_redemption_insurance_fee = (float) ($this->data['mrif'] ?? 0);
        $product->maximum_paying_age = (float) ($this->data['maximum_paying_age'] ?? 0);
        $product->income_requirement_multiplier = (float) ($this->data['income_requirement_multiplier'] ?? 0);
        $product->processing_fee = (float) ($this->data['processing_fee'] ?? 0);
        $product->price = (float) ($this->data['typical_price'] ?? 0);
        $product->project_code=(string) ($this->data['project_code'] ?? '');
        $product->property_type=(string) ($this->data['property_type'] ?? '');
        $product->house_type=(string) ($this->data['house_type'] ?? '');
        $product->unit_type=(string) ($this->data['unit_type'] ?? '');
        $product->balance_payment_term = (float) ($this->data['bp_term'] ?? 0);
        $product->floor_area = (float) ($this->data['floor_area'] ?? 0);
        $product->lot_area = (float) ($this->data['lot_area'] ?? 0);
        $product->phased_out = (bool) ($this->data['phased_out'] ?? false);

        $product->save();
        return $product;
//        return new Product();
    }

    protected function beforeSave(): void
    {
        $facadeUrl = $this->data['facade_url'] ?? null;
        $this->record = Product::firstOrCreate(
            ['sku' => (string) ($this->data['sku'] ?? '')],
            [
                'name' => (string) ($this->data['name'] ?? ''),
                'brand' => (string) ($this->data['brand'] ?? ''),
                'category' => (string) ($this->data['category'] ?? ''),
                'bank' => (bool) ($this->data['bank'] ?? false),
                'hdmf' => (bool) ($this->data['hdmf'] ?? false),
                'preferred_option' => (string) ($this->data['preferred_option'] ?? ''),
                'description' => (string) ($this->data['description'] ?? ''),
                'price' => (float) ($this->data['typical_price'] ?? 0),
                'reservation_fee' => (float) ($this->data['reservation_fee'] ?? 0),
                'location' => (string) ($this->data['location'] ?? ''),
                'directions' => (string) ($this->data['directions'] ?? ''),
                'amenities' => (string) ($this->data['amenities'] ?? ''),
                'facade_url' => (string) ($facadeUrl ?? ''),
                'destinations' => (string) ($this->data['destinations'] ?? ''),
            ]
        );
        $this->record->facade_url= $facadeUrl ?? '';
        $this->record->directions= $this->data['directions'] ?? '';
        $this->record->name= $this->data['name'] ?? '';
        $this->record->brand= $this->data['brand'] ?? '';
        $this->record->bank= $this->data['bank'] ?? false;
        $this->record->hdmf= $this->data['hdmf'] ?? false;
        $this->record->preferred_option= $this->data['preferred_option'] ?? '';
        $this->record->category= $this->data['category'] ?? '';
        $this->record->description= $this->data['description'] ?? '';
        $this->record->destinations= $this->data['destinations'] ?? '';
        $this->record->amenities= $this->data['amenities'] ?? '';

        $this->record->key_location= $this->data['key_location'] ?? '';
        $this->record->percent_down_payment=(float) ($this->data['percent_dp'] ?? 0);
        $this->record->down_payment_term=(float) ($this->data['dp_term'] ?? 0);
        $this->record->percent_miscellaneous_fees=(float) ($this->data['percent_mf'] ?? 0);
        $this->record->digital_assets=(string) ($this->data['digital_assets'] ?? '');

        $this->record->percent_gross_monthly_income = (float) ($this->data['percent_gmi'] ?? 0);
        $this->record->balance_payment_interest_rate = (float) ($this->data['balance_payment_interest_rate'] ?? 0);
        $this->record->max_age = (float) ($this->data['max_age'] ?? 0);
        $this->record->mortgage_redemption_insurance_fee = (float) ($this->data['mrif'] ?? 0);
        $this->record->maximum_paying_age = (float) ($this->data['maximum_paying_age'] ?? 0);
        $this->record->income_requirement_multiplier = (float) ($this->data['income_requirement_multiplier'] ?? 0);
        $this->record->processing_fee = (float) ($this->data['processing_fee'] ?? 0);
        $this->record->price = (float) ($this->data['typical_price'] ?? 0);
        $this->record->project_code=(string) ($this->data['project_code'] ?? '');
        $this->record->property_type=(string) ($this->data['property_type'] ?? '');
        $this->record->house_type=(string) ($this->data['house_type'] ?? '');
        $this->record->unit_type=(string) ($this->data['unit_type'] ?? '');
        $this->record->balance_payment_term = (float) ($this->data['bp_term'] ?? 0);
        $this->record->floor_area = (float) ($this->data['floor_area'] ?? 0);
        $this->record->lot_area = (float) ($this->data['lot_area'] ?? 0);
        $this->record->phased_out = (bool) ($this->data['phased_out'] ?? false);

        $this->record->save();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your product import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}

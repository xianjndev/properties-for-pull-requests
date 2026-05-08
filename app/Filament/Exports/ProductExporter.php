<?php

namespace App\Filament\Exports;

use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Homeful\Products\Models\Product;
use Homeful\Properties\Models\Property;

class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('sku'),
            ExportColumn::make('name')
                ->state(function (Product $record) {
                    return $record->name;
                }),
            ExportColumn::make('brand')
                ->state(function (Product $record) {
                    return $record->brand;
                }),
            ExportColumn::make('category')
                ->state(function (Product $record) {
                    return $record->category;
                }),
            ExportColumn::make('hdmf')
                ->state(function (Product $record) {
                    return $record->hdmf;
                }),
            ExportColumn::make('bank')
                ->state(function (Product $record) {
                    return $record->bank;
                }),
            ExportColumn::make('preferred_option')
                ->state(function (Product $record) {
                    return $record->preferred_option;
                }),
            ExportColumn::make('reservation_fee')
                ->state(function (Product $record) {
                    return $record->reservation_fee;
                }),
            ExportColumn::make('description')
                ->state(function (Product $record) {
                    return $record->description;
                }),
            ExportColumn::make('price')
                ->state(function (Product $record) {
                    return $record->price;
                }),
            ExportColumn::make('destinations')
                ->state(function (Product $record) {
                    return $record->destinations;
                }),
            ExportColumn::make('directions')
                ->state(function (Product $record) {
                    return $record->directions;
                }),
            ExportColumn::make('amenities')
                ->state(function (Product $record) {
                    return $record->amenities;
                }),
            ExportColumn::make('facade_url')
                ->state(function (Product $record) {
                    return $record->facade_url;
                }),
            ExportColumn::make('lot_area')
                ->state(function (Product $record) {
                    return $record->meta->get('lot_area');
                }),
            ExportColumn::make('floor_area')
                ->state(function (Product $record) {
                    return $record->meta->get('floor_area');
                }),
            ExportColumn::make('unit_type')
                ->state(function (Product $record) {
                    return $record->meta->get('unit_type');
                }),
            ExportColumn::make('appraised_value')
                ->state(function (Product $record) {
                    return $record->appraised_value;
                }),
            ExportColumn::make('percent_down_payment')
                ->label('Percent Down Payment')
                ->state(function (Product $record) {
                    return $record->meta->get('percent_down_payment');
                }),
            ExportColumn::make('down_payment_term')
                ->label('Down Payment Term')
                ->state(function (Product $record) {
                    return $record->meta->get('down_payment_term');
                }),
            ExportColumn::make('balance_payment_term')
                ->label('Balance Payment Term')
                ->state(function (Product $record) {
                    return $record->meta->get('balance_payment_term');
                }),
            ExportColumn::make('percent_mf')
                ->state(function (Product $record) {
                    return $record->meta->get('percent_mf');
                }),
            ExportColumn::make('key_location')
                ->state(function (Product $record) {
                    return $record->meta->get('key_location');
                }),
            ExportColumn::make('digital_assets')
                ->state(function (Product $record) {
                    return $record->meta->get('digital_assets');
                }),
            ExportColumn::make('percent_gmi')
                ->state(function (Product $record) {
                    return $record->meta->get('percent_gmi');
                }),
            ExportColumn::make('max_age')
                ->state(function (Product $record) {
                    return $record->meta->get('max_age');
                }),
            ExportColumn::make('interest_rate')
                ->state(function (Product $record) {
                    return $record->meta->get('interest_rate');
                }),
            ExportColumn::make('mortgage_redemption_insurance_fee')
                ->state(function (Product $record) {
                    return $record->meta->get('mortgage_redemption_insurance_fee');
                }),
        ];
    }
    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your product export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}

<?php

namespace App\Filament\Resources\ProductsResource\Pages;

use App\Filament\Exports\ProductExporter;
use App\Filament\Imports\ProductImporter;
use App\Filament\Resources\ProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;
use Homeful\Products\Models\Product;

class ManageProducts extends ManageRecords
{
    protected static string $resource = ProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    return $data;
                })
                ->using(function (array $data, $model): Model {
                    $model = Product::create($data);
                    $model->destinations = $data['destinations'];
                    $model->facade_url = $data['facade_url'];
                    $model->directions = $data['directions'];
                    $model->amenities = $data['amenities'];
                    $model->key_location = $data['key_location'];
                    $model->percent_down_payment = $data['percent_down_payment'];
                    $model->down_payment_term = $data['down_payment_term'];
                    $model->percent_miscellaneous_fees = $data['percent_miscellaneous_fees'];
                    $model->digital_assets = $data['digital_assets'];
                    $model->percent_gross_monthly_income = $data['percent_gross_monthly_income'];
                    $model->balance_payment_interest_rate = $data['balance_payment_interest_rate'];
                    $model->max_age = $data['max_age'];
                    $model->mortgage_redemption_insurance_fee = $data['mortgage_redemption_insurance_fee'];
                    $model->maximum_paying_age = $data['maximum_paying_age'];
                    $model->income_requirement_multiplier = $data['income_requirement_multiplier'];
                    $model->processing_fee = $data['processing_fee'];
                    $model->project_code = $data['project_code'];
                    $model->property_type = $data['property_type'];
                    $model->house_type = $data['house_type'];
                    $model->unit_type = $data['unit_type'];
                    $model->balance_payment_term = $data['balance_payment_term'];
                    $model->floor_area = $data['floor_area'];
                    $model->lot_area = $data['lot_area'];
                    $model->phased_out = $data['phased_out'];
                    $model->save();
                    return $model;
                }),
            Actions\ImportAction::make()
                ->hidden(auth()->user()?->cannot('import_products'))
                ->importer(ProductImporter::class),
            Actions\ExportAction::make()
                ->hidden(auth()->user()?->cannot('export_products'))
                ->exporter(ProductExporter::class),
        ];
    }

}

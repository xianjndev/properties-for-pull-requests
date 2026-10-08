<?php

namespace App\Filament\Exports;

use App\Models\TechnicalDescription;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TechnicalDescriptionExporter extends Exporter
{
    protected static ?string $model = TechnicalDescription::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('property_code')->label('Property Code'),
            ExportColumn::make('company_name')->label('Company Name'),
            ExportColumn::make('registry_of_deeds')->label('Registry of Deeds'),
            ExportColumn::make('tct')->label('TCT'),
            ExportColumn::make('vsr')->label('VSR'),
            ExportColumn::make('survey_plan_no')->label('Survey Plan No.'),
            ExportColumn::make('block_no')->label('Block No.'),
            ExportColumn::make('lot_no')->label('Lot No.'),
            ExportColumn::make('portion_of_lot')->label('Portion of Lot'),
            ExportColumn::make('lrc_record_no')->label('LRC Record No.'),
            ExportColumn::make('land_owner_claimant')->label('Land Owner/Claimant'),
            ExportColumn::make('location')->label('Location'),
            ExportColumn::make('area')->label('Area'),
            ExportColumn::make('description_of_corners')->label('Description of Corners'),
            ExportColumn::make('bearings')
                ->label('Bearings')
                ->formatStateUsing(fn (mixed $state): string => $state ? 'True' : 'False'),
            ExportColumn::make('original_date_of_survey')->label('Original Date of Survey'),
            ExportColumn::make('date_of_survey')->label('Date of Survey'),
            ExportColumn::make('date_approved')->label('Date Approved'),
            ExportColumn::make('geodetic_engineer')->label('Geodetic Engineer'),
            ExportColumn::make('old_technical_desc')->label('Old Technical Description'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your technical description export has completed and '
            .number_format($export->successful_rows).' '
            .str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '
                .str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}

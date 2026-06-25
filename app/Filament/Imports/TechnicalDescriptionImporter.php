<?php

namespace App\Filament\Imports;

use App\Models\TechnicalDescription;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class TechnicalDescriptionImporter extends Importer
{
    protected static ?string $model = TechnicalDescription::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('property_code')
                ->label('Property Code')
                ->requiredMapping()
                ->rules([
                    'required',
                    'string',
                    'max:255',
                ]),
            ImportColumn::make('company_name')
                ->label('Company Name')
                ->requiredMapping()
                ->rules([
                    'required',
                    'string',
                    'max:255',
                ]),
            ImportColumn::make('registry_of_deeds')
                ->label('Registry of Deeds')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('tct')
                ->label('TCT')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('vsr')
                ->label('VSR')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('survey_plan_no')
                ->label('Survey Plan No.')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('block_no')
                ->label('Block No.')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('lot_no')
                ->label('Lot No.')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('portion_of_lot')
                ->label('Portion of Lot')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('lrc_record_no')
                ->label('LRC Record No.')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('land_owner_claimant')
                ->label('Land Owner/Claimant')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('location')
                ->label('Location')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('area')
                ->label('Area')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('description_of_corners')
                ->label('Description of Corners')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('bearings')
                ->label('Bearings')
                ->boolean()
                ->requiredMapping()
                ->rules(['required', 'boolean']),
            ImportColumn::make('original_date_of_survey')
                ->label('Original Date of Survey')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('date_of_survey')
                ->label('Date of Survey')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('date_approved')
                ->label('Date Approved')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('geodetic_engineer')
                ->label('Geodetic Engineer')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('old_technical_desc')
                ->label('Old Technical Description')
                ->guess(['old_technical_desc', 'property_code_desc'])
                ->rules(['nullable', 'string']),
        ];
    }

    public function resolveRecord(): ?TechnicalDescription
    {
        return TechnicalDescription::firstOrNew([
            'property_code' => (string) $this->data['property_code'],
        ]);
    }

    public function getJobConnection(): ?string
    {
        return 'sync';
    }

    public static function getCompletedNotificationTitle(Import $import): string
    {
        return 'Technical description import completed';
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your technical descriptions import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}

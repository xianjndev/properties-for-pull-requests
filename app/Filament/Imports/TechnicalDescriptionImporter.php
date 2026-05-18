<?php

namespace App\Filament\Imports;

use App\Filament\Resources\TechnicalDescriptionResource;
use App\Models\Property;
use App\Models\TechnicalDescription;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
                    Rule::exists('properties', 'code'),
                    Rule::unique('technical_descriptions', 'property_code'),
                    fn () => function (string $attribute, mixed $value, \Closure $fail): void {
                        if (! TechnicalDescriptionResource::isRayvanesPropertyCode((string) $value)) {
                            $fail('Property Code must belong to a Rayvanes Realty Corp / RRC project.');
                        }
                    },
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
            ImportColumn::make('technical_description')
                ->label('Technical Description')
                ->requiredMapping()
                ->rules(['required', 'string']),
        ];
    }

    public function resolveRecord(): ?TechnicalDescription
    {
        return new TechnicalDescription();
    }

    protected function afterValidate(): void
    {
        $property = Property::query()
            ->where('code', (string) $this->data['property_code'])
            ->first();

        if (! $property) {
            return;
        }

        $companyName = TechnicalDescriptionResource::getRayvanesCompanyName($property);

        if (strcasecmp(trim((string) $this->data['company_name']), trim((string) $companyName)) === 0) {
            $this->ensurePropertyCodeIsUniqueInImport();

            return;
        }

        throw ValidationException::withMessages([
            'company_name' => 'Company Name must match the selected Property Code company name.',
        ]);
    }

    protected function ensurePropertyCodeIsUniqueInImport(): void
    {
        $propertyCode = trim((string) ($this->data['property_code'] ?? ''));

        if ($propertyCode === '') {
            return;
        }

        $cacheKey = sprintf(
            'technical-description-import:%s:property-code:%s',
            $this->import->getKey(),
            hash('sha256', strtolower($propertyCode)),
        );

        if (Cache::add($cacheKey, true, now()->addDay())) {
            return;
        }

        throw ValidationException::withMessages([
            'property_code' => 'Property Code is duplicated in the uploaded file.',
        ]);
    }

    protected function beforeSave(): void
    {
        $property = Property::query()
            ->where('code', (string) $this->data['property_code'])
            ->first();

        if ($property) {
            $this->record->company_name = TechnicalDescriptionResource::getRayvanesCompanyName($property);
        }
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
        $body = 'Your technical descriptions import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}

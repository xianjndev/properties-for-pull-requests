<?php

namespace App\Filament\Imports;

use App\Filament\Resources\TechnicalDescriptionResource;
use App\Models\Project;
use App\Models\TechnicalDescription;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TechnicalDescriptionImporter extends Importer
{
    protected static ?string $model = TechnicalDescription::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('project_code')
                ->label('Project Code')
                ->requiredMapping()
                ->rules([
                    'required',
                    'string',
                    'max:255',
                    Rule::exists('projects', 'code'),
                    Rule::unique('technical_descriptions', 'project_code'),
                    fn () => function (string $attribute, mixed $value, \Closure $fail): void {
                        if (! TechnicalDescriptionResource::isRayvanesProjectCode((string) $value)) {
                            $fail('Project Code must belong to Rayvanes Realty Corp / RRC.');
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
        $project = Project::query()
            ->where('code', (string) $this->data['project_code'])
            ->first();

        if (! $project) {
            return;
        }

        $companyName = TechnicalDescriptionResource::getRayvanesCompanyName($project);

        if (strcasecmp(trim((string) $this->data['company_name']), trim((string) $companyName)) === 0) {
            return;
        }

        throw ValidationException::withMessages([
            'company_name' => 'Company Name must match the selected Project Code company name.',
        ]);
    }

    protected function beforeSave(): void
    {
        $project = Project::query()
            ->where('code', (string) $this->data['project_code'])
            ->first();

        if ($project) {
            $this->record->company_name = TechnicalDescriptionResource::getRayvanesCompanyName($project);
        }
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

<?php

namespace App\Filament\Imports;

use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Homeful\Properties\Models\Project;
use Homeful\Property\Enums\MarketSegment;
use Illuminate\Support\Carbon;

class ProjectsImporter extends Importer
{
    protected static ?string $model = Project::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->guess(['code','project_code'])
                ->rules(['max:255']),
            ImportColumn::make('name')
                ->guess(['name','project_name'])
                ->rules(['max:255']),
            ImportColumn::make('location')
                ->guess(['location','project_location'])
                ->rules(['max:255']),
            ImportColumn::make('address')
                ->guess(['address','project_address'])
                ->rules(['max:255']),
            ImportColumn::make('type')
                ->guess(['type','project_type'])
                ->label('Market Segment')
                ->rules(['max:255']),
            ImportColumn::make('housingType')
                ->guess(['housing_type'])
                ->rules(['max:255']),
            ImportColumn::make('licenseNumber')
                ->guess(['license_number','project_license_number'])
                ->rules(['max:255']),
            ImportColumn::make('licenseDate')
                ->guess(['license_date','project_license_date'])
                ->fillRecordUsing(function (Project $record, string $state): void {
                    $record->licenseNumber = $state;
                })
                ->rules(['max:255']),
            ImportColumn::make('company_name')
                ->rules(['max:255']),
            ImportColumn::make('company_code')
                ->rules(['max:255']),
            ImportColumn::make('company_tin')
                ->rules(['max:255']),
            ImportColumn::make('company_address')
                ->rules(['max:255']),
            ImportColumn::make('pagibig_filing_site')
                ->rules(['max:255']),
            ImportColumn::make('filing_site')
                ->rules(['max:255']),
            ImportColumn::make('exec_position')
                ->rules(['max:255']),
            ImportColumn::make('exec_signatory')
                ->rules(['max:255']),
            ImportColumn::make('exec_tin')
                ->rules(['max:255']),
            ImportColumn::make('board_resolution_date')
                ->rules(['max:255']),
            ImportColumn::make('appraised_lot_value')
                ->rules(['max:255']),
            ImportColumn::make('total_sold'),
        ];
    }

    public function resolveRecord(): ?Project
    {
        try {
            $project = Project::firstOrNew(
                [
                    'name' => $this->data['project_name'],
                    'code'=>$this->data['project_code'],
                ],[
                'location'=>$this->data['project_location'],
            ]);
            $project->meta->set('address', $this->data['project_address']??'');
//        $project->meta->set('type',strtoupper($this->data['project_type']));
//        $marketSegment = match (strtoupper($this->data['project_type'])) {
//            'OPEN' => MarketSegment::OPEN,
//            'ECONOMIC' => MarketSegment::ECONOMIC,
//            'SOCIALIZED' => MarketSegment::SOCIALIZED,
//            default => throw new \InvalidArgumentException("Invalid MarketSegment: {$this->data['project_type']}"),
//        };
//        $project->meta->set('type', $marketSegment);
//        $project->type = $marketSegment;

            $project->meta->set('housingType', $this->data['housing_type']??'');
            $project->meta->set('licenseNumber', $this->data['licenseNumber']??'');

//            $project->meta->set('licenseDate', $this->data['license_date']);
            $project->meta->set('license_date',
                !empty($this->data['license_date'])
                    ? Carbon::parse($this->data['license_date'])->format('Y-m-d')
                    : null
            );

            $project->meta->set('company_code', $this->data['company_code']??'');
            $project->meta->set('appraised_lot_value',(float) $this->data['appraised_lot_value']??0);
            $project->meta->set('appraised__lot_value',(float) $this->data['appraised_lot_value']??0);
            $project->meta->set('total_sold', $this->data['total_sold']);

            $project->meta->set('company_name', $this->data['company_name']??'');
            $project->meta->set('company_tin', $this->data['company_tin']??'');
            $project->meta->set('company_address', $this->data['company_address']??'');
            $project->meta->set('pagibig_filing_site', $this->data['pagibig_filing_site']??'');
            $project->meta->set('filing_site', $this->data['filing_site']??'');
            $project->meta->set('exec_position', $this->data['exec_position']??'');
            $project->meta->set('exec_signatory', $this->data['exec_signatory']??'');
            $project->meta->set('exec_tin', $this->data['exec_tin']??'');
            $project->meta->set('board_resolution_date', !empty($this->data['board_resolution_date'])
                ? Carbon::parse($this->data['board_resolution_date'])->format('Y-m-d')
                : null
            );
            $project->save();


        } catch (\Throwable $th) {
            dd($th);
        }

        return $project;
    }

    public function beforeSave(): void
    {
        $this->record = Project::firstOrNew(
            [
                'name' => $this->data['project_name'],
                'code'=>$this->data['project_code'],
            ],[
            'location'=>$this->data['project_location'],
        ]);
        $this->record->meta->set('address', $this->data['project_address']);

//        $this->record->meta->set('type',strtoupper($this->data['project_type']));
        $marketSegment = match (strtoupper($this->data['project_type'])) {
            'OPEN' => MarketSegment::OPEN,
            'ECONOMIC' => MarketSegment::ECONOMIC,
            'SOCIALIZED' => MarketSegment::SOCIALIZED,
            default => throw new \InvalidArgumentException("Invalid MarketSegment: {$this->data['project_type']}"),
        };
//        $project->meta->set('type', $marketSegment);
        $this->record->type = $marketSegment;


        $this->record->meta->set('housingType', $this->data['housing_type']);
        $this->record->meta->set('licenseNumber', $this->data['licenseNumber']);
        $this->record->meta->set('licenseDate', 
            !empty($this->data['license_date'])
                ? Carbon::parse($this->data['license_date'])->format('Y-m-d')
                : null
        );
        $this->record->meta->set('company_code', $this->data['company_code']);
        $this->record->meta->set('appraised_lot_value',(float) $this->data['appraised_lot_value']??0);
        $this->record->meta->set('appraised__lot_value',(float) $this->data['appraised_lot_value']??0);
        $this->record->meta->set('total_sold', $this->data['total_sold']);
        $this->record->company_name = $this->data['company_name'];
        $this->record->company_tin = $this->data['company_tin'];
        $this->record->company_address = $this->data['company_address'];
        $this->record->pagibig_filing_site = $this->data['pagibig_filing_site'];
        $this->record->filing_site = $this->data['filing_site'];
        $this->record->exec_position = $this->data['exec_position'];
        $this->record->exec_signatory = $this->data['exec_signatory'];
        $this->record->exec_tin = $this->data['exec_tin'];
        $this->record->meta->set('board_resolution_date', !empty($this->data['board_resolution_date'])
            ? Carbon::parse($this->data['board_resolution_date'])->format('Y-m-d')
            : null
        );

        $this->record->save();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your projects import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}

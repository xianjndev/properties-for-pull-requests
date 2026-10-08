<?php

namespace App\Filament\Resources\TechnicalDescriptionResource\Pages;

use App\Filament\Exports\TechnicalDescriptionExporter;
use App\Filament\Imports\TechnicalDescriptionImporter;
use App\Filament\Resources\TechnicalDescriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTechnicalDescriptions extends ManageRecords
{
    protected static string $resource = TechnicalDescriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ImportAction::make()
                ->importer(TechnicalDescriptionImporter::class),
            Actions\ExportAction::make()
                ->hidden(auth()->user()?->cannot('export_technical::description'))
                ->exporter(TechnicalDescriptionExporter::class),
        ];
    }
}

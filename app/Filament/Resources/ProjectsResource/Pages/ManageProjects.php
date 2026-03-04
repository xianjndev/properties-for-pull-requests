<?php

namespace App\Filament\Resources\ProjectsResource\Pages;

use App\Filament\Imports\ProductsImportImporter;
use App\Filament\Imports\ProjectsImporter;
use App\Filament\Resources\ProjectsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Homeful\Properties\Models\Project;
use Illuminate\Database\Eloquent\Model;

class ManageProjects extends ManageRecords
{
    protected static string $resource = ProjectsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function (array $data, string $model): Project {
                    $project = Project::create([
                        'name' => $data['name'],
                        'code'=>$data['code'],
                        'location'=>$data['location'],
                    ]);
                    $project->meta->set('address', $data['address']);
                    $project->meta->set('type', $data['type']);
                    $project->meta->set('housingType', $data['housingType']);
                    $project->meta->set('licenseNumber', $data['licenseNumber']);
                    $project->meta->set('licenseDate', $data['licenseDate']);
                    $project->meta->set('company_code', $data['company_code']);
                    $project->meta->set('appraised_lot_value', $data['appraised_lot_value']);
                    $project->meta->set('appraised__lot_value', $data['appraised_lot_value']);
                    $project->save();

                    return $project;
                }),
            Actions\ImportAction::make()
                ->importer(ProjectsImporter::class)
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \Coolsam\NestedComments\Filament\Widgets\CommentsWidget::class,
        ];
    }
}

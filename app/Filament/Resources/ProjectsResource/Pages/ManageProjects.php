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
                    $project->meta->set('housingType', $data['housingType']);
                    $project->meta->set('licenseNumber', $data['licenseNumber']);
                    $project->meta->set('license_date', $data['license_date']);
                    $project->meta->set('company_code', $data['company_code']);
                    $project->meta->set('appraised_lot_value', $data['appraised_lot_value']);
                    $project->meta->set('total_sold', $data['total_sold']);
                    $project->meta->set('company_name', $data['company_name']);
                    $project->meta->set('company_tin', $data['company_tin']);
                    $project->meta->set('company_address', $data['company_address']);
                    $project->meta->set('pagibig_filing_site', $data['pagibig_filing_site']);
                    $project->meta->set('exec_position', $data['exec_position']);
                    $project->meta->set('exec_signatory', $data['exec_signatory']);
                    $project->meta->set('exec_tin', $data['exec_tin']);
                    $project->meta->set('board_resolution_date', $data['board_resolution_date']);
                    $project->meta->set('type', $data['type']);
                    $project->meta->set('licenseDate', $data['licenseDate']);
                    $project->meta->set('project_description', $data['project_description']);
                    $project->meta->set('filing_site', $data['filing_site']);
                    
                    $project->save();

                    return $project;
                }),
            Actions\ImportAction::make()
                ->importer(ProjectsImporter::class)
        ];
    }
}

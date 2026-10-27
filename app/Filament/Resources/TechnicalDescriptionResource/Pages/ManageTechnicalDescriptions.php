<?php

namespace App\Filament\Resources\TechnicalDescriptionResource\Pages;

use App\Filament\Imports\TechnicalDescriptionImporter;
use App\Filament\Resources\TechnicalDescriptionResource;
use App\Jobs\SyncMFilesInventories;
use App\Models\TechnicalDescriptionSyncRun;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ManageTechnicalDescriptions extends ManageRecords
{
    protected static string $resource = TechnicalDescriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('syncMfilesInventories')
                ->label('M-Files Sync Inventories')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->visible(fn (): bool => auth()->user()?->can('create_technical::description') === true
                    && auth()->user()?->can('update_technical::description') === true)
                ->requiresConfirmation()
                ->modalHeading('Sync inventories from M-Files?')
                ->modalDescription('Enter either a project code or a property code. M-Files will update the matching technical descriptions and record each changed field in the sync logs.')
                ->modalSubmitActionLabel('Sync Inventories')
                ->form([
                    TextInput::make('projectCode')
                        ->label('Project Code')
                        ->helperText('Enter a project code or a property code below, but not both.')
                        ->maxLength(255)
                        ->rules([
                            'nullable',
                            'string',
                            Rule::exists('projects', 'code'),
                        ])
                        ->validationMessages([
                            'exists' => 'This is not a valid project code. If this is a property code, enter it in the Property Code field.',
                        ])
                        ->requiredWithout('propertyCode')
                        ->prohibits('propertyCode')
                        ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? trim($state) : null),
                    TextInput::make('propertyCode')
                        ->label('Property Code')
                        ->helperText('Enter a property code only when Project Code is empty.')
                        ->maxLength(255)
                        ->rules([
                            'nullable',
                            'string',
                            Rule::exists('properties', 'code'),
                        ])
                        ->validationMessages([
                            'exists' => 'This is not a valid property code. If this is a project code, enter it in the Project Code field.',
                        ])
                        ->requiredWithout('projectCode')
                        ->prohibits('projectCode')
                        ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? trim($state) : null),
                ])
                ->action(function (array $data): void {
                    $syncRequestId = (string) Str::uuid();
                    $filters = array_filter([
                        'projectCode' => $data['projectCode'] ?? null,
                        'propertyCode' => $data['propertyCode'] ?? null,
                    ], filled(...));

                    Log::info('M-Files inventory sync requested.', [
                        'sync_request_id' => $syncRequestId,
                        'url' => config('services.mfiles.sync_inventories_url'),
                        'filters' => $filters,
                        'user_id' => auth()->id(),
                    ]);

                    $filterType = isset($filters['propertyCode']) ? 'property_code' : 'project_code';
                    $filterValue = $filters['propertyCode'] ?? $filters['projectCode'];
                    $syncRun = TechnicalDescriptionSyncRun::create([
                        'sync_request_id' => $syncRequestId,
                        'filter_type' => $filterType,
                        'filter_value' => $filterValue,
                        'status' => 'queued',
                        'user_id' => auth()->id(),
                    ]);

                    SyncMFilesInventories::dispatch($filters, $syncRequestId, auth()->id(), $syncRun->getKey())
                        ->onConnection('database-mfiles')
                        ->onQueue('mfiles-sync');

                    Notification::make()
                        ->title('M-Files sync started')
                        ->body("The sync is running in the background. Request ID: {$syncRequestId}")
                        ->info()
                        ->send();
                }),
            Actions\CreateAction::make(),
            Actions\ImportAction::make()
                ->importer(TechnicalDescriptionImporter::class),
        ];
    }
}

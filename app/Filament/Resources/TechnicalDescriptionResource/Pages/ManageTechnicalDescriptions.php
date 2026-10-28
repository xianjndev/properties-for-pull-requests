<?php

namespace App\Filament\Resources\TechnicalDescriptionResource\Pages;

use App\Filament\Imports\TechnicalDescriptionImporter;
use App\Filament\Resources\TechnicalDescriptionResource;
use App\Models\TechnicalDescriptionSyncRun;
use App\Services\MFilesApiLogger;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
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

                    $activeRun = TechnicalDescriptionSyncRun::query()
                        ->where('filter_type', $filterType)
                        ->where('filter_value', $filterValue)
                        ->whereIn('status', ['queued', 'running'])
                        ->first();

                    if ($activeRun !== null) {
                        Notification::make()
                            ->title('M-Files sync already active')
                            ->body("A sync for {$filterValue} is already queued or running. Request ID: {$activeRun->sync_request_id}")
                            ->warning()
                            ->send();

                        return;
                    }

                    $syncRun = TechnicalDescriptionSyncRun::create([
                        'sync_request_id' => $syncRequestId,
                        'filter_type' => $filterType,
                        'filter_value' => $filterValue,
                        'status' => 'queued',
                        'user_id' => auth()->id(),
                    ]);

                    $url = config('services.mfiles.sync_inventories_url');
                    $token = config('services.mfiles.token');
                    $startedAt = hrtime(true);

                    try {
                        if (blank($url) || blank($token)) {
                            throw new \RuntimeException('M-Files sync configuration is incomplete.');
                        }

                        $response = Http::acceptJson()
                            ->withToken($token)
                            ->withHeader('X-Sync-Request-ID', $syncRequestId)
                            ->withoutRedirecting()
                            ->connectTimeout(10)
                            ->timeout(60)
                            ->post($url, $filters);
                        $result = is_array($response->json()) ? $response->json() : [
                            'raw' => Str::limit($response->body(), 10000),
                        ];

                        app(MFilesApiLogger::class)->log(
                            eventType: $response->status() === 202 ? 'sync_initiated' : 'sync_initiation_failed',
                            apiCalled: $url,
                            syncRequestId: $syncRequestId,
                            requestBody: ['filters' => $filters],
                            response: $result,
                            statusCode: $response->status(),
                            durationMs: (int) round((hrtime(true) - $startedAt) / 1_000_000),
                        );

                        if ($response->status() !== 202) {
                            throw new \RuntimeException("M-Files returned HTTP {$response->status()} instead of 202 Accepted.");
                        }
                    } catch (\Throwable $exception) {
                        $statusCode = $exception instanceof RequestException
                            ? $exception->response->status()
                            : null;

                        if (! isset($response)) {
                            app(MFilesApiLogger::class)->log(
                                eventType: 'sync_initiation_failed',
                                apiCalled: (string) $url,
                                syncRequestId: $syncRequestId,
                                requestBody: ['filters' => $filters],
                                response: [
                                    'failure_type' => $statusCode === null ? 'transport_or_runtime_error' : 'http_error',
                                    'exception' => $exception::class,
                                    'message' => $exception->getMessage(),
                                ],
                                statusCode: $statusCode,
                                durationMs: (int) round((hrtime(true) - $startedAt) / 1_000_000),
                            );
                        }

                        $syncRun->forceFill([
                            'status' => 'failed',
                            'error_message' => $exception->getMessage(),
                            'finished_at' => now(),
                        ])->save();

                        Log::error('Unable to initiate M-Files inventory sync.', [
                            'sync_request_id' => $syncRequestId,
                            'exception' => $exception,
                        ]);

                        Notification::make()
                            ->title('Unable to start M-Files sync')
                            ->body("{$exception->getMessage()} Request ID: {$syncRequestId}")
                            ->danger()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('M-Files sync queued')
                        ->body("M-Files accepted the sync for background processing. Request ID: {$syncRequestId}")
                        ->info()
                        ->send();
                }),
            Actions\CreateAction::make(),
            Actions\ImportAction::make()
                ->importer(TechnicalDescriptionImporter::class),
        ];
    }
}

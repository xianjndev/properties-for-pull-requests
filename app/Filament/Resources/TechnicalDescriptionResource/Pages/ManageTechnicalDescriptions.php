<?php

namespace App\Filament\Resources\TechnicalDescriptionResource\Pages;

use App\Filament\Imports\TechnicalDescriptionImporter;
use App\Filament\Resources\TechnicalDescriptionResource;
use App\Models\Property;
use App\Models\TechnicalDescriptionSyncRun;
use App\Services\MFilesApiLogger;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

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
                ->modalDescription('Enter a project code, a property code, or upload a CSV containing selected property codes. Use only one option.')
                ->modalSubmitActionLabel('Sync Inventories')
                ->form([
                    TextInput::make('projectCode')
                        ->label('Project Code')
                        ->helperText('Enter a project code, a property code below, or upload a CSV. Use only one option.')
                        ->maxLength(255)
                        ->rules([
                            'nullable',
                            'string',
                            Rule::exists('projects', 'code'),
                        ])
                        ->validationMessages([
                            'exists' => 'This is not a valid project code. If this is a property code, enter it in the Property Code field.',
                        ])
                        ->requiredWithoutAll(['propertyCode', 'propertyCodesCsv'])
                        ->prohibits(['propertyCode', 'propertyCodesCsv'])
                        ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? trim($state) : null),
                    TextInput::make('propertyCode')
                        ->label('Property Code')
                        ->helperText('Enter a property code only when Project Code and CSV are empty.')
                        ->maxLength(255)
                        ->rules([
                            'nullable',
                            'string',
                            Rule::exists('properties', 'code'),
                        ])
                        ->validationMessages([
                            'exists' => 'This is not a valid property code. If this is a project code, enter it in the Project Code field.',
                        ])
                        ->requiredWithoutAll(['projectCode', 'propertyCodesCsv'])
                        ->prohibits(['projectCode', 'propertyCodesCsv'])
                        ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? trim($state) : null),
                    FileUpload::make('propertyCodesCsv')
                        ->label('Selected Property Codes CSV')
                        ->helperText('Upload a CSV with a property_code, property code, or code header. A headerless single-column CSV is also accepted.')
                        ->acceptedFileTypes(['text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel'])
                        ->maxSize(2048)
                        ->storeFiles(false)
                        ->requiredWithoutAll(['projectCode', 'propertyCode'])
                        ->prohibits(['projectCode', 'propertyCode']),
                ])
                ->action(function (array $data, Form $form): void {
                    $syncRequestId = (string) Str::uuid();
                    $propertyCodes = isset($data['propertyCodesCsv'])
                        ? $this->validatedPropertyCodesFromCsv(
                            $data['propertyCodesCsv'],
                            $form->getStatePath().'.propertyCodesCsv',
                        )
                        : [];
                    $filters = array_filter([
                        'projectCode' => $data['projectCode'] ?? null,
                        'propertyCode' => $data['propertyCode'] ?? null,
                        'propertyCodes' => $propertyCodes,
                    ], filled(...));

                    Log::info('M-Files inventory sync requested.', [
                        'sync_request_id' => $syncRequestId,
                        'url' => config('services.mfiles.sync_inventories_url'),
                        'filters' => $propertyCodes === [] ? $filters : [
                            'selected_property_count' => count($propertyCodes),
                            'sample_property_codes' => array_slice($propertyCodes, 0, 10),
                        ],
                        'user_id' => auth()->id(),
                    ]);

                    if ($propertyCodes !== []) {
                        $hashCodes = $propertyCodes;
                        sort($hashCodes);
                        $filterType = 'selected_property_codes';
                        $filterValue = sprintf(
                            'CSV: %d properties [%s]',
                            count($propertyCodes),
                            substr(hash('sha256', implode("\n", $hashCodes)), 0, 12),
                        );
                    } else {
                        $filterType = isset($filters['propertyCode']) ? 'property_code' : 'project_code';
                        $filterValue = $filters['propertyCode'] ?? $filters['projectCode'];
                    }

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

    private function validatedPropertyCodesFromCsv(mixed $upload, string $validationKey): array
    {
        if (! $upload instanceof TemporaryUploadedFile) {
            throw ValidationException::withMessages([
                $validationKey => 'Please upload a valid CSV file.',
            ]);
        }

        $file = new \SplFileObject($upload->getRealPath());
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

        $normalize = fn (mixed $value): string => str((string) $value)
            ->replace("\xEF\xBB\xBF", '')
            ->trim()
            ->toString();
        $propertyCodes = [];
        $columnIndex = 0;

        foreach ($file as $rowNumber => $row) {
            if (! is_array($row)) {
                continue;
            }

            if ($rowNumber === 0) {
                $headers = array_map(
                    fn (mixed $value): string => str($normalize($value))
                        ->lower()
                        ->replace([' ', '-'], '_')
                        ->toString(),
                    $row,
                );
                $headerIndex = collect($headers)->search(
                    fn (string $header): bool => in_array($header, ['property_code', 'propertycode', 'code'], true),
                );

                if ($headerIndex !== false) {
                    $columnIndex = (int) $headerIndex;

                    continue;
                }
            }

            $propertyCode = $normalize($row[$columnIndex] ?? '');

            if ($propertyCode !== '') {
                $propertyCodes[] = $propertyCode;
            }
        }

        $propertyCodes = array_values(array_unique($propertyCodes));

        if ($propertyCodes === []) {
            throw ValidationException::withMessages([
                $validationKey => 'The CSV must contain at least one property code.',
            ]);
        }

        if (count($propertyCodes) > 5000) {
            throw ValidationException::withMessages([
                $validationKey => 'The CSV may contain at most 5,000 unique property codes.',
            ]);
        }

        $existingCodes = Property::query()
            ->whereIn('code', $propertyCodes)
            ->pluck('code')
            ->map(fn (mixed $code): string => (string) $code)
            ->all();
        $invalidCodes = array_values(array_diff($propertyCodes, $existingCodes));

        if ($invalidCodes !== []) {
            $sample = implode(', ', array_slice($invalidCodes, 0, 10));
            $remaining = count($invalidCodes) - min(10, count($invalidCodes));

            throw ValidationException::withMessages([
                $validationKey => 'Unknown property codes: '.$sample.($remaining > 0 ? " and {$remaining} more." : '.'),
            ]);
        }

        return $propertyCodes;
    }
}

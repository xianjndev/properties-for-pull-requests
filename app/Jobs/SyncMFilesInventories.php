<?php

namespace App\Jobs;

use App\Models\TechnicalDescriptionSyncLog;
use App\Models\TechnicalDescriptionSyncRun;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncMFilesInventories implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 21600;

    public bool $failOnTimeout = true;

    /**
     * @param  array<string, string>  $filters
     */
    public function __construct(
        public array $filters,
        public string $syncRequestId,
        public ?int $userId,
        public int $syncRunId,
    ) {}

    public function handle(): void
    {
        $started = TechnicalDescriptionSyncRun::query()
            ->whereKey($this->syncRunId)
            ->where('status', 'queued')
            ->update([
                'status' => 'running',
                'started_at' => now(),
                'finished_at' => null,
                'error_message' => null,
            ]);

        if ($started !== 1) {
            Log::info('M-Files sync job was not started because the run is no longer queued.', [
                'sync_run_id' => $this->syncRunId,
                'sync_request_id' => $this->syncRequestId,
            ]);

            return;
        }

        $url = config('services.mfiles.sync_inventories_url');
        $token = config('services.mfiles.token');

        if (blank($url) || blank($token)) {
            Log::warning('M-Files inventory sync is not configured.', [
                'sync_request_id' => $this->syncRequestId,
                'url_configured' => filled($url),
                'token_configured' => filled($token),
                'user_id' => $this->userId,
            ]);
            $this->recordFailure('M-Files sync configuration is incomplete.');
            $this->finishRun('failed', 'M-Files sync configuration is incomplete.');
            $this->notifyUser(
                Notification::make()
                    ->title('Unable to sync inventories')
                    ->body("The M-Files sync service is currently unavailable. Request ID: {$this->syncRequestId}")
                    ->danger()
            );

            return;
        }

        $startedAt = hrtime(true);
        $mfilesResponseLogged = false;

        try {
            $response = Http::acceptJson()
                ->withToken($token)
                ->withHeader('X-Sync-Request-ID', $this->syncRequestId)
                ->connectTimeout(30)
                ->timeout(21500)
                ->get($url, $this->filters);

            $decodedResponse = $response->json();
            $result = is_array($decodedResponse) ? $decodedResponse : [];

            $this->sendMFilesApiLog(
                apiCalled: $url,
                requestBody: [
                    'query' => $this->filters,
                    'headers' => ['X-Sync-Request-ID' => $this->syncRequestId],
                ],
                response: $result !== [] ? $this->summarizeMFilesResponse($result) : ['raw' => Str::limit($response->body(), 10000)],
                statusCode: $response->status(),
                durationMs: (int) round((hrtime(true) - $startedAt) / 1_000_000),
            );
            $mfilesResponseLogged = true;

            Log::info('M-Files inventory sync response received.', [
                'sync_request_id' => $this->syncRequestId,
                'status' => $response->status(),
                'message' => $result['message'] ?? null,
                'success' => $result['success'] ?? null,
                'failed' => $result['failed'] ?? null,
                'total' => $result['total'] ?? null,
            ]);

            if ($response->failed()) {
                Log::error('M-Files inventory sync returned an unsuccessful response.', [
                    'url' => $url,
                    'sync_request_id' => $this->syncRequestId,
                    'status' => $response->status(),
                    'response_body' => Str::limit($response->body(), 2000),
                    'filters' => $this->filters,
                    'user_id' => $this->userId,
                ]);
                $this->recordFailure("M-Files returned HTTP {$response->status()}.", [
                    'response' => $result !== [] ? $result : Str::limit($response->body(), 2000),
                ]);
                $this->finishRun('failed', "M-Files returned HTTP {$response->status()}.", $result);
                $this->notifyUser(
                    Notification::make()
                        ->title('Unable to sync inventories')
                        ->body("M-Files returned an error. Request ID: {$this->syncRequestId}")
                        ->danger()
                );

                return;
            }

            if (($result['status'] ?? null) === 'stopped') {
                $runUpdated = $this->finishRun(
                    'failed',
                    'M-Files stopped because Properties rejected a progress callback.',
                    $result,
                );

                Log::warning('M-Files stopped the inventory synchronization.', [
                    'sync_run_id' => $this->syncRunId,
                    'sync_request_id' => $this->syncRequestId,
                    'run_updated' => $runUpdated,
                    'mfiles_success' => $result['success'] ?? null,
                    'mfiles_failed' => $result['failed'] ?? null,
                    'mfiles_total' => $result['total'] ?? null,
                ]);

                return;
            }

            $successCount = (int) ($result['success'] ?? 0);
            $failedCount = (int) ($result['failed'] ?? 0);
            $totalCount = (int) ($result['total'] ?? ($successCount + $failedCount));
            $this->reconcileResultItems($result['items'] ?? []);

            $reconciliation = $this->reconcileFinalCounts($successCount, $failedCount, $totalCount, $result);

            if (! $reconciliation['finalized']) {
                Log::warning('M-Files sync result was not finalized because the run is no longer active.', [
                    'sync_run_id' => $this->syncRunId,
                    'sync_request_id' => $this->syncRequestId,
                    'attempted_status' => $reconciliation['status'],
                    'current_status' => TechnicalDescriptionSyncRun::find($this->syncRunId)?->status,
                ]);

                return;
            }

            if ($reconciliation['status'] === 'completed_with_errors') {
                Log::warning('M-Files inventory sync completed with errors.', [
                    'sync_request_id' => $this->syncRequestId,
                    'empty_result' => $reconciliation['empty_result'],
                    'reported_failed' => $failedCount,
                    'reported_total' => $totalCount,
                    'local_failed' => $reconciliation['failed'],
                    'local_total' => $reconciliation['total'],
                    'reconciled' => $reconciliation['reconciled'],
                ]);
            }

            if ($reconciliation['empty_result']) {
                $this->notifyUser(
                    Notification::make()
                        ->title('No inventories found')
                        ->body("No inventories matched the supplied filter, so nothing was synchronized. Request ID: {$this->syncRequestId}")
                        ->warning()
                );

                return;
            }

            $notification = Notification::make()
                ->title($reconciliation['status'] === 'completed_with_errors' ? 'M-Files sync completed with errors' : 'M-Files sync completed')
                ->body("{$reconciliation['success']} succeeded and {$reconciliation['failed']} failed out of {$reconciliation['total']} stored inventories. Request ID: {$this->syncRequestId}");

            $this->notifyUser($reconciliation['status'] === 'completed_with_errors' ? $notification->warning() : $notification->success());
        } catch (\Throwable $exception) {
            $statusCode = $exception instanceof RequestException
                ? $exception->response->status()
                : null;

            if (! $mfilesResponseLogged) {
                $this->sendMFilesApiLog(
                    apiCalled: $url,
                    requestBody: [
                        'query' => $this->filters,
                        'headers' => ['X-Sync-Request-ID' => $this->syncRequestId],
                    ],
                    response: [
                        'failure_type' => $statusCode === null
                            ? 'transport_or_runtime_error'
                            : 'http_error',
                        'exception' => $exception::class,
                        'message' => $exception->getMessage(),
                    ],
                    statusCode: $statusCode,
                    durationMs: (int) round((hrtime(true) - $startedAt) / 1_000_000),
                );
            }

            Log::error('M-Files inventory sync failed with an exception.', [
                'url' => $url,
                'sync_request_id' => $this->syncRequestId,
                'filters' => $this->filters,
                'user_id' => $this->userId,
                'http_status' => $statusCode,
                'exception' => $exception,
            ]);
            throw $exception;
        }
    }

    public function failed(?\Throwable $exception): void
    {
        $message = $exception?->getMessage() ?? 'The M-Files inventory sync failed.';
        $timedOut = $exception instanceof \Illuminate\Queue\TimeoutExceededException;

        Log::error('M-Files inventory sync job failed.', [
            'sync_request_id' => $this->syncRequestId,
            'filters' => $this->filters,
            'user_id' => $this->userId,
            'exception' => $exception,
        ]);
        $this->recordFailure($message, [
            'exception' => $exception === null ? null : $exception::class,
        ]);
        $this->finishRun($timedOut ? 'timed_out' : 'failed', $message);
        $this->notifyUser(
            Notification::make()
                ->title('Unable to sync inventories')
                ->body("The background sync did not finish. Request ID: {$this->syncRequestId}")
                ->danger()
        );
    }

    private function notifyUser(Notification $notification): void
    {
        $user = $this->userId === null ? null : User::find($this->userId);

        if ($user !== null) {
            $notification->sendToDatabase($user);
        }
    }

    private function sendMFilesApiLog(
        string $apiCalled,
        array $requestBody,
        array $response,
        ?int $statusCode,
        int $durationMs,
    ): void {
        $logUrl = config('services.match.mfiles_technical_description_api_logs_url');
        $logToken = config('services.match.token');

        if (blank($logUrl) || blank($logToken)) {
            Log::warning('Match M-Files technical description API logging is not configured.', [
                'url_configured' => filled($logUrl),
                'token_configured' => filled($logToken),
            ]);

            return;
        }

        try {
            $logResponse = Http::acceptJson()
                ->withToken($logToken)
                ->timeout(30)
                ->post($logUrl, [
                    'api_called' => $apiCalled,
                    'sync_request_id' => $this->syncRequestId,
                    'status_code' => $statusCode,
                    'duration_ms' => $durationMs,
                    'request_body' => $requestBody,
                    'response' => $response,
                ]);

            if ($logResponse->failed()) {
                Log::warning('Match rejected the M-Files technical description API log.', [
                    'status' => $logResponse->status(),
                    'response_body' => Str::limit($logResponse->body(), 2000),
                ]);
            }
        } catch (\Throwable $exception) {
            Log::warning('Unable to send the M-Files technical description API log to Match.', [
                'exception_class' => $exception::class,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private function summarizeMFilesResponse(array $response): array
    {
        $items = is_array($response['items'] ?? null) ? $response['items'] : [];
        $failedItems = collect($items)
            ->filter(fn (mixed $item): bool => is_array($item) && ($item['status'] ?? null) === 'failed')
            ->take(20)
            ->map(fn (array $item): array => [
                'propertyCode' => $item['propertyCode'] ?? null,
                'status' => 'failed',
                'message' => Str::limit((string) ($item['message'] ?? ''), 1000),
            ])->values()->all();

        return [
            'message' => isset($response['message']) ? Str::limit((string) $response['message'], 1000) : null,
            'success' => $response['success'] ?? null,
            'failed' => $response['failed'] ?? null,
            'total' => $response['total'] ?? null,
            'sample_failures' => $failedItems,
            'items_truncated' => count($items) > count($failedItems),
            'item_count' => count($items),
        ];
    }

    private function recordFailure(string $message, array $details = []): void
    {
        $scope = isset($this->filters['propertyCode'])
            ? $this->filters['propertyCode']
            : (isset($this->filters['projectCode']) ? "Project {$this->filters['projectCode']}" : 'Unknown sync request');

        try {
            TechnicalDescriptionSyncLog::recordFailure(
                propertyCode: $scope,
                action: 'sync_failed',
                details: [
                    'message' => $message,
                    'sync_request_id' => $this->syncRequestId,
                    ...$details,
                ],
                userId: $this->userId,
                syncRunId: $this->syncRunId,
            );
        } catch (\Throwable $loggingException) {
            Log::error('Unable to persist M-Files sync request failure log.', [
                'sync_request_id' => $this->syncRequestId,
                'scope' => $scope,
                'failure_message' => $message,
                'logging_exception' => $loggingException->getMessage(),
            ]);
        }
    }

    private function finishRun(string $status, ?string $errorMessage = null, array $summary = [], ?int $total = null): bool
    {
        $updated = TechnicalDescriptionSyncRun::query()
            ->whereKey($this->syncRunId)
            ->whereIn('status', ['queued', 'running'])
            ->update([
                'status' => $status,
                'error_message' => $errorMessage,
                'summary' => $summary === [] ? null : $summary,
                'finished_at' => now(),
            ]);

        if ($updated !== 1) {
            return false;
        }

        TechnicalDescriptionSyncRun::find($this->syncRunId)?->refreshCounts($total);

        return true;
    }

    private function reconcileResultItems(array $items): void
    {
        foreach ($items as $item) {
            $propertyCode = $item['propertyCode'] ?? null;
            $status = $item['status'] ?? null;

            if (! is_string($propertyCode) || ! in_array($status, ['created', 'updated', 'unchanged', 'failed'], true)) {
                continue;
            }

            $log = TechnicalDescriptionSyncLog::firstOrNew([
                'sync_run_id' => $this->syncRunId,
                'property_code' => $propertyCode,
            ]);

            if (! $log->exists) {
                $log->changes = [];
            }

            $log->fill([
                'action' => $status,
                'status' => $status,
                'error_message' => $status === 'failed' ? ($item['message'] ?? 'M-Files processing failed.') : null,
                'user_id' => $this->userId,
            ])->save();
        }
    }

    /**
     * @return array{status: string, reconciled: bool, success: int, failed: int, total: int, finalized: bool, empty_result: bool}
     */
    private function reconcileFinalCounts(int $reportedSuccess, int $reportedFailed, int $reportedTotal, array $summary): array
    {
        $syncRun = TechnicalDescriptionSyncRun::findOrFail($this->syncRunId);
        $syncRun->refreshCounts();
        $syncRun->refresh();

        $localSuccess = $syncRun->created_count + $syncRun->updated_count + $syncRun->unchanged_count;
        $localFailed = $syncRun->failed_count;
        $localTotal = $localSuccess + $localFailed;
        $reconciled = $reportedTotal === $reportedSuccess + $reportedFailed
            && $reportedTotal === $localTotal
            && $reportedSuccess === $localSuccess
            && $reportedFailed === $localFailed;
        $emptyResult = $reportedTotal === 0 && $localTotal === 0;

        $message = match (true) {
            $emptyResult => 'No inventories matched the supplied filter, so nothing was synchronized.',
            $reconciled => null,
            default => sprintf(
                'Count mismatch: M-Files reported total=%d, success=%d, failed=%d; stored items total=%d, success=%d, failed=%d.',
                $reportedTotal,
                $reportedSuccess,
                $reportedFailed,
                $localTotal,
                $localSuccess,
                $localFailed,
            ),
        };
        $status = $reconciled && $localFailed === 0 && ! $emptyResult
            ? 'completed'
            : 'completed_with_errors';

        $finalized = TechnicalDescriptionSyncRun::query()
            ->whereKey($this->syncRunId)
            ->where('status', 'running')
            ->update([
                'status' => $status,
                'total_count' => $localTotal,
                'reported_success_count' => $reportedSuccess,
                'reported_failed_count' => $reportedFailed,
                'result_reconciled' => $reconciled,
                'error_message' => $message,
                'summary' => $summary,
                'finished_at' => now(),
            ]) === 1;

        return [
            'status' => $status,
            'reconciled' => $reconciled,
            'success' => $localSuccess,
            'failed' => $localFailed,
            'total' => $localTotal,
            'finalized' => $finalized,
            'empty_result' => $emptyResult,
        ];
    }
}

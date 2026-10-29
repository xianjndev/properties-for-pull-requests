<?php

namespace App\Http\Controllers;

use App\Models\TechnicalDescriptionSyncRun;
use App\Models\User;
use App\Services\MFilesApiLogger;
use Filament\Notifications\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CompleteTechnicalDescriptionSyncController extends Controller
{
    public function __invoke(Request $request, MFilesApiLogger $apiLogger): JsonResponse
    {
        $startedAt = hrtime(true);
        $validated = $request->validate([
            'status' => ['required', Rule::in(['completed', 'completed_with_errors', 'failed', 'stopped'])],
            'success' => ['required', 'integer', 'min:0'],
            'failed' => ['required', 'integer', 'min:0'],
            'total' => ['required', 'integer', 'min:0'],
            'message' => ['nullable', 'string', 'max:5000'],
        ]);
        $syncRequestId = $request->header('X-Sync-Request-ID');

        validator(
            ['sync_request_id' => $syncRequestId],
            ['sync_request_id' => ['required', 'uuid']],
        )->validate();

        $result = DB::transaction(function () use ($syncRequestId, $validated): array {
            $syncRun = TechnicalDescriptionSyncRun::query()
                ->where('sync_request_id', $syncRequestId)
                ->lockForUpdate()
                ->firstOrFail();

            if (! in_array($syncRun->status, ['queued', 'running'], true)) {
                return [
                    'sync_run' => $syncRun,
                    'duplicate' => true,
                    'response' => [
                        'success' => true,
                        'message' => 'The sync completion was already recorded.',
                        'status' => $syncRun->status,
                    ],
                ];
            }

            $syncRun->refreshCounts($validated['total']);
            $syncRun->refresh();

            $localSuccess = $syncRun->created_count + $syncRun->updated_count + $syncRun->unchanged_count;
            $localFailed = $syncRun->failed_count;
            $localTotal = $localSuccess + $localFailed;
            $reportedCountsAreValid = $validated['total'] === $validated['success'] + $validated['failed'];
            $reconciled = $reportedCountsAreValid
                && $validated['total'] === $localTotal
                && $validated['success'] === $localSuccess
                && $validated['failed'] === $localFailed;
            $emptyResult = in_array($validated['status'], ['completed', 'completed_with_errors'], true)
                && $validated['total'] === 0
                && $localTotal === 0;

            $message = match (true) {
                $emptyResult => 'No inventories matched the supplied filter, so nothing was synchronized.',
                ! $reconciled && in_array($validated['status'], ['completed', 'completed_with_errors'], true) => sprintf(
                    'Count mismatch: M-Files reported total=%d, success=%d, failed=%d; stored items total=%d, success=%d, failed=%d.',
                    $validated['total'],
                    $validated['success'],
                    $validated['failed'],
                    $localTotal,
                    $localSuccess,
                    $localFailed,
                ),
                filled($validated['message'] ?? null) => $validated['message'],
                default => null,
            };

            $status = match ($validated['status']) {
                'failed', 'stopped' => 'failed',
                default => $reconciled && $localFailed === 0 && ! $emptyResult
                    ? 'completed'
                    : 'completed_with_errors',
            };

            $syncRun->forceFill([
                'status' => $status,
                'total_count' => max($validated['total'], $localTotal),
                'processed_count' => $localTotal,
                'reported_success_count' => $validated['success'],
                'reported_failed_count' => $validated['failed'],
                'result_reconciled' => $reconciled,
                'error_message' => $status === 'completed' ? null : $message,
                'summary' => $validated,
                'finished_at' => now(),
            ])->save();

            return [
                'sync_run' => $syncRun,
                'duplicate' => false,
                'response' => [
                    'success' => true,
                    'message' => 'The sync completion was recorded.',
                    'status' => $status,
                    'reconciled' => $reconciled,
                    'local_success' => $localSuccess,
                    'local_failed' => $localFailed,
                    'local_total' => $localTotal,
                ],
            ];
        });

        $syncRun = $result['sync_run'];

        if (! $result['duplicate']) {
            $this->notifyUser($syncRun);

            $apiLogger->log(
                eventType: match ($syncRun->status) {
                    'completed' => 'sync_completed',
                    'completed_with_errors' => 'sync_completed_with_errors',
                    default => 'sync_failed',
                },
                apiCalled: $request->fullUrl(),
                syncRequestId: $syncRequestId,
                requestBody: $validated,
                response: $result['response'],
                statusCode: 200,
                durationMs: (int) round((hrtime(true) - $startedAt) / 1_000_000),
            );

            Log::info('M-Files inventory sync completion recorded.', [
                'sync_request_id' => $syncRequestId,
                ...$result['response'],
            ]);
        }

        return response()->json($result['response']);
    }

    private function notifyUser(TechnicalDescriptionSyncRun $syncRun): void
    {
        $user = $syncRun->user_id === null ? null : User::find($syncRun->user_id);

        if ($user === null) {
            return;
        }

        $notification = Notification::make()
            ->title(match ($syncRun->status) {
                'completed' => 'M-Files sync completed',
                'completed_with_errors' => $syncRun->total_count === 0 ? 'No inventories found' : 'M-Files sync completed with errors',
                default => 'M-Files sync failed',
            })
            ->body(($syncRun->error_message ?: "{$syncRun->processed_count} of {$syncRun->total_count} inventories were processed.")." Request ID: {$syncRun->sync_request_id}");

        (match ($syncRun->status) {
            'completed' => $notification->success(),
            'completed_with_errors' => $notification->warning(),
            default => $notification->danger(),
        })->sendToDatabase($user);
    }
}

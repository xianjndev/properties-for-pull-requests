<?php

namespace App\Http\Controllers;

use App\Models\TechnicalDescriptionSyncLog;
use App\Models\TechnicalDescriptionSyncRun;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateTechnicalDescriptionSyncProgressController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'property_code' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['pending', 'processing', 'failed'])],
            'message' => ['nullable', 'string', 'max:5000'],
            'total' => ['nullable', 'integer', 'min:0'],
        ]);

        $syncRun = TechnicalDescriptionSyncRun::query()
            ->where('sync_request_id', $request->header('X-Sync-Request-ID'))
            ->firstOrFail();

        abort_unless(
            in_array($syncRun->status, ['queued', 'running'], true),
            409,
            'The technical description sync request is no longer active.',
        );

        if ($syncRun->status === 'queued') {
            $syncRun->forceFill([
                'status' => 'running',
                'started_at' => $syncRun->started_at ?? now(),
            ])->save();
        }

        $log = TechnicalDescriptionSyncLog::firstOrNew([
            'sync_run_id' => $syncRun->getKey(),
            'property_code' => $validated['property_code'],
        ]);

        if (! $log->exists) {
            $log->changes = [];
        }

        $log->fill([
            'action' => $validated['status'],
            'status' => $validated['status'],
            'error_message' => $validated['message'] ?? null,
            'user_id' => $syncRun->user_id,
        ])->save();

        $syncRun->refreshCounts($validated['total'] ?? null);

        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TechnicalDescriptionSyncRun extends Model
{
    protected $fillable = [
        'sync_request_id',
        'filter_type',
        'filter_value',
        'status',
        'total_count',
        'processed_count',
        'created_count',
        'updated_count',
        'unchanged_count',
        'failed_count',
        'error_message',
        'summary',
        'user_id',
        'started_at',
        'finished_at',
        'reported_success_count',
        'reported_failed_count',
        'result_reconciled',
    ];

    protected function casts(): array
    {
        return [
            'summary' => 'array',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'result_reconciled' => 'boolean',
        ];
    }

    public function logs(): HasMany
    {
        return $this->hasMany(TechnicalDescriptionSyncLog::class, 'sync_run_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function refreshCounts(?int $reportedTotal = null): void
    {
        $counts = $this->logs()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $processed = collect(['created', 'updated', 'unchanged', 'failed'])
            ->sum(fn (string $status): int => (int) ($counts[$status] ?? 0));

        $this->forceFill([
            'total_count' => max($reportedTotal ?? 0, (int) $this->total_count, $this->logs()->count()),
            'processed_count' => $processed,
            'created_count' => (int) ($counts['created'] ?? 0),
            'updated_count' => (int) ($counts['updated'] ?? 0),
            'unchanged_count' => (int) ($counts['unchanged'] ?? 0),
            'failed_count' => (int) ($counts['failed'] ?? 0),
        ])->save();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalDescriptionSyncLog extends Model
{
    protected $fillable = [
        'sync_run_id',
        'technical_description_id',
        'property_code',
        'action',
        'status',
        'changes',
        'error_message',
        'user_id',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function technicalDescription(): BelongsTo
    {
        return $this->belongsTo(TechnicalDescription::class);
    }

    public function syncRun(): BelongsTo
    {
        return $this->belongsTo(TechnicalDescriptionSyncRun::class, 'sync_run_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function recordFailure(
        string $propertyCode,
        string $action,
        array $details,
        ?int $userId = null,
        ?int $technicalDescriptionId = null,
        ?int $syncRunId = null,
    ): self {
        $attributes = [
            'technical_description_id' => $technicalDescriptionId,
            'property_code' => $propertyCode,
            'action' => $action,
            'status' => 'failed',
            'error_message' => $details['message'] ?? null,
            'changes' => [
                'failure' => [
                    'old' => null,
                    'new' => $details,
                ],
            ],
            'user_id' => $userId,
        ];

        if ($syncRunId === null) {
            return self::create($attributes);
        }

        return self::updateOrCreate([
            'sync_run_id' => $syncRunId,
            'property_code' => $propertyCode,
        ], $attributes);
    }

    /**
     * @return array<int, array{field: string, old: string, new: string}>
     */
    public function getFormattedChangesAttribute(): array
    {
        return collect($this->getAttribute('changes') ?? [])
            ->map(fn (array $change, string $field): array => [
                'field' => str($field)->replace('_', ' ')->title()->toString(),
                'old' => self::displayValue($change['old'] ?? null),
                'new' => self::displayValue($change['new'] ?? null),
            ])
            ->values()
            ->all();
    }

    private static function displayValue(mixed $value): string
    {
        if ($value === null) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '[]';
        }

        return (string) $value;
    }
}

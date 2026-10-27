<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TechnicalDescriptionSyncLogResource\Pages;
use App\Filament\Resources\TechnicalDescriptionSyncLogResource\RelationManagers\ItemsRelationManager;
use App\Models\TechnicalDescriptionSyncRun;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TechnicalDescriptionSyncLogResource extends Resource
{
    protected static ?string $model = TechnicalDescriptionSyncRun::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    protected static ?string $navigationLabel = 'Technical Description Syncs';

    protected static ?string $modelLabel = 'Technical Description Sync';

    protected static ?string $recordTitleAttribute = 'sync_request_id';

    protected static ?int $navigationSort = 30;

    public static function table(Table $table): Table
    {
        return $table
            ->poll('5s')
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->persistFiltersInSession()
            ->columns([
                TextColumn::make('created_at')->label('Started')->since()->dateTimeTooltip()->sortable(),
                TextColumn::make('filter_type')->label('Sync By')->badge()->formatStateUsing(fn (string $state): string => str($state)->headline()),
                TextColumn::make('filter_value')->label('Filter')->weight('medium')->copyable()->searchable()->sortable(),
                TextColumn::make('status')->badge()->formatStateUsing(fn (string $state): string => str($state)->headline())->color(fn (string $state): string => self::statusColor($state)),
                TextColumn::make('progress')->state(fn (TechnicalDescriptionSyncRun $record): string => "{$record->processed_count} / {$record->total_count}")->label('Progress'),
                TextColumn::make('created_count')->label('Created')->badge()->color('success'),
                TextColumn::make('updated_count')->label('Updated')->badge()->color('info'),
                TextColumn::make('unchanged_count')->label('Unchanged')->badge()->color('gray'),
                TextColumn::make('failed_count')->label('Failed')->badge()->color('danger'),
                TextColumn::make('result_reconciled')->label('Reconciled')->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No')
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning'),
                TextColumn::make('user.name')->label('Triggered By')->icon('heroicon-m-user')->placeholder('System'),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'queued' => 'Queued',
                    'running' => 'Running',
                    'completed' => 'Completed',
                    'completed_with_errors' => 'Completed With Errors',
                    'failed' => 'Failed',
                    'timed_out' => 'Timed Out',
                ]),
                Filter::make('created_at')->label('Sync date')->form([
                    DatePicker::make('from')->label('From'),
                    DatePicker::make('until')->label('Until'),
                ])->query(fn (Builder $query, array $data): Builder => $query
                    ->when($data['from'] ?? null, fn (Builder $query, string $date): Builder => $query->whereDate('created_at', '>=', $date))
                    ->when($data['until'] ?? null, fn (Builder $query, string $date): Builder => $query->whereDate('created_at', '<=', $date))),
            ])
            ->actions([Tables\Actions\ViewAction::make()->tooltip('View sync and item details')])
            ->recordAction('view')
            ->emptyStateIcon('heroicon-o-clipboard-document-check')
            ->emptyStateHeading('No sync activity yet');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Sync overview')->schema([
                TextEntry::make('sync_request_id')->label('Request ID')->copyable(),
                TextEntry::make('filter_type')->label('Sync By')->formatStateUsing(fn (string $state): string => str($state)->headline()),
                TextEntry::make('filter_value')->label('Filter')->copyable(),
                TextEntry::make('status')->badge()->formatStateUsing(fn (string $state): string => str($state)->headline())->color(fn (string $state): string => self::statusColor($state)),
                TextEntry::make('total_count')->label('Total'),
                TextEntry::make('processed_count')->label('Processed'),
                TextEntry::make('created_count')->label('Created'),
                TextEntry::make('updated_count')->label('Updated'),
                TextEntry::make('unchanged_count')->label('Unchanged'),
                TextEntry::make('failed_count')->label('Failed'),
                TextEntry::make('reported_success_count')->label('Reported Success')->placeholder('Not reported'),
                TextEntry::make('reported_failed_count')->label('Reported Failed')->placeholder('Not reported'),
                TextEntry::make('result_reconciled')->label('Counts Reconciled')->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No'),
                TextEntry::make('started_at')->dateTime()->placeholder('Not started'),
                TextEntry::make('finished_at')->dateTime()->placeholder('Still running'),
                TextEntry::make('error_message')->columnSpanFull()->placeholder('None'),
            ])->columns(4),
        ]);
    }

    public static function getRelations(): array
    {
        return [ItemsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTechnicalDescriptionSyncLogs::route('/'),
            'view' => Pages\ViewTechnicalDescriptionSyncLog::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view_any_technical::description') === true;
    }

    public static function canView($record): bool
    {
        return auth()->user()?->can('view_technical::description') === true;
    }

    private static function statusColor(string $status): string
    {
        return match ($status) {
            'completed' => 'success',
            'running' => 'info',
            'queued', 'completed_with_errors' => 'warning',
            'failed', 'timed_out' => 'danger',
            default => 'gray',
        };
    }
}

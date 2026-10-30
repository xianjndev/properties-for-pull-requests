<?php

namespace App\Filament\Resources\TechnicalDescriptionSyncLogResource\RelationManagers;

use App\Models\TechnicalDescriptionSyncLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'logs';

    protected static ?string $title = 'Properties';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('property_code')->disabled(),
            Forms\Components\TextInput::make('status')->disabled(),
            Forms\Components\Textarea::make('error_message')->disabled()->columnSpanFull(),
            Forms\Components\Textarea::make('changes')
                ->formatStateUsing(fn (mixed $state): string => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '{}')
                ->disabled()->rows(16)->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->poll('5s')
            ->defaultSort('updated_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('property_code')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->formatStateUsing(fn (string $state): string => str($state)->headline())
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated', 'processing' => 'info',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('changed_fields')
                    ->state(fn (TechnicalDescriptionSyncLog $record): array => array_keys($record->changes ?? []))
                    ->badge()->placeholder('No field changes'),
                Tables\Columns\TextColumn::make('error_message')->limit(60)->placeholder('None'),
                Tables\Columns\TextColumn::make('updated_at')->label('Last Update')->since()->dateTimeTooltip(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'created' => 'Created',
                    'updated' => 'Updated',
                    'failed' => 'Failed',
                ]),
            ])
            ->actions([Tables\Actions\ViewAction::make()]);
    }
}

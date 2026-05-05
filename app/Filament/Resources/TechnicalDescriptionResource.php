<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TechnicalDescriptionResource\Pages;
use App\Models\Project;
use App\Models\TechnicalDescription;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TechnicalDescriptionResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = TechnicalDescription::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Technical Descriptions';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'import',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_code')
                    ->label('Project Code')
                    ->options(fn (): array => Project::query()
                        ->orderBy('code')
                        ->get()
                        ->filter(fn (Project $project): bool => self::isRayvanesProject($project))
                        ->mapWithKeys(fn (Project $project): array => [$project->code => $project->code])
                        ->toArray())
                    ->searchable()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->rules([
                        fn () => function (string $attribute, mixed $value, \Closure $fail): void {
                            if (! self::isRayvanesProjectCode((string) $value)) {
                                $fail('Technical descriptions may only be created for Rayvanes Realty Corp / RRC projects.');
                            }
                        },
                    ])
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, ?string $state): void {
                        $project = Project::query()
                            ->where('code', $state)
                            ->first();

                        $set('company_name', self::getRayvanesCompanyName($project));
                    })
                    ->columnSpan(3),
                Forms\Components\TextInput::make('company_name')
                    ->label('Company Name')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->maxLength(255)
                    ->columnSpan(3),
                Forms\Components\TextInput::make('registry_of_deeds')
                    ->label('Registry of Deeds')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(3),
                Forms\Components\TextInput::make('tct')
                    ->label('TCT')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(3),
                Forms\Components\TextInput::make('vsr')
                    ->label('VSR')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(3),
                Forms\Components\Textarea::make('technical_description')
                    ->label('Technical Description')
                    ->required()
                    ->columnSpanFull()
                    ->rows(10),
            ])
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('project_code')
                    ->label('Project Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('company_name')
                    ->label('Company Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('registry_of_deeds')
                    ->label('Registry of Deeds')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tct')
                    ->label('TCT')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vsr')
                    ->label('VSR')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('technical_description')
                    ->label('Technical Description')
                    ->searchable()
                    ->words(15),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTechnicalDescriptions::route('/'),
        ];
    }

    public static function isRayvanesProjectCode(string $projectCode): bool
    {
        $project = Project::query()
            ->where('code', $projectCode)
            ->first();

        if (! $project) {
            return false;
        }

        return self::isRayvanesProject($project);
    }

    public static function isRayvanesProject(Project $project): bool
    {
        return strcasecmp(trim((string) $project->company_code), 'RRC') === 0
            || strcasecmp(trim((string) $project->company_name), 'Rayvanes Realty Corp') === 0;
    }

    public static function getRayvanesCompanyName(?Project $project): ?string
    {
        if (! $project) {
            return null;
        }

        return $project->company_name ?: 'Rayvanes Realty Corp';
    }
}

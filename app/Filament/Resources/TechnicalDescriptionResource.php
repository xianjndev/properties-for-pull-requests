<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TechnicalDescriptionResource\Pages;
use App\Models\Property;
use App\Models\TechnicalDescription;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;

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
            'export',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('property_code')
                    ->label('Property Code')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->rules([
                        Rule::exists('properties', 'code'),
                    ])
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, ?string $state): void {
                        $property = Property::query()
                            ->where('code', $state)
                            ->first();

                        $set('company_name', self::getPropertyCompanyName($property));
                    })
                    ->columnSpan(3),
                Forms\Components\TextInput::make('company_name')
                    ->label('Company Name')
                    ->required()
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
                Forms\Components\TextInput::make('survey_plan_no')
                    ->label('Survey Plan No.')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(3),
                Forms\Components\TextInput::make('block_no')
                    ->label('Block No.')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(3),
                Forms\Components\TextInput::make('lot_no')
                    ->label('Lot No.')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(3),
                Forms\Components\TextInput::make('lrc_record_no')
                    ->label('LRC Record No.')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(3),
                Forms\Components\TextInput::make('land_owner_claimant')
                    ->label('Land Owner/Claimant')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(6),
                Forms\Components\TextInput::make('area')
                    ->label('Area')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(6),
                Forms\Components\Textarea::make('portion_of_lot')
                    ->label('Portion of Lot')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('location')
                    ->label('Location')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description_of_corners')
                    ->label('Description of Corners')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('bearings')
                    ->label('Bearings')
                    ->required()
                    ->columnSpan(3),
                Forms\Components\TextInput::make('original_date_of_survey')
                    ->label('Original Date of Survey')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(3),
                Forms\Components\TextInput::make('date_of_survey')
                    ->label('Date of Survey')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(3),
                Forms\Components\TextInput::make('date_approved')
                    ->label('Date Approved')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(3),
                Forms\Components\TextInput::make('geodetic_engineer')
                    ->label('Geodetic Engineer')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(6),
                Forms\Components\Textarea::make('old_technical_desc')
                    ->label('Old Technical Description')
                    ->rows(6)
                    ->columnSpanFull(),
            ])
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('property_code')
                    ->label('Property Code')
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
                TextColumn::make('survey_plan_no')
                    ->label('Survey Plan No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('block_no')
                    ->label('Block No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('lot_no')
                    ->label('Lot No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('land_owner_claimant')
                    ->label('Land Owner/Claimant')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location')
                    ->label('Location')
                    ->searchable()
                    ->words(8),
                TextColumn::make('area')
                    ->label('Area')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('geodetic_engineer')
                    ->label('Geodetic Engineer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('portion_of_lot')
                    ->label('Portion of Lot')
                    ->searchable()
                    ->words(15)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('description_of_corners')
                    ->label('Description of Corners')
                    ->searchable()
                    ->words(15)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bearings')
                    ->label('Bearings')
                    ->badge()
                    ->formatStateUsing(fn (mixed $state): string => $state ? 'True' : 'False')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('original_date_of_survey')
                    ->label('Original Date of Survey')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('date_of_survey')
                    ->label('Date of Survey')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('date_approved')
                    ->label('Date Approved')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('old_technical_desc')
                    ->label('Old Technical Description')
                    ->searchable()
                    ->words(15)
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

    public static function getPropertyCompanyName(?Property $property): ?string
    {
        $project = $property?->project;

        if (! $project) {
            return null;
        }

        return $project->company_name;
    }
}

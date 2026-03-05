<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductsResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Homeful\Products\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductsResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('sku')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->columnSpan(3),
                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->columnSpan(5),
                        Forms\Components\TextInput::make('brand')
                                            ->required()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('category')
                                            ->required()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('price')
                                            ->numeric()
                                            ->required()
                                            ->columnSpan(3),
                        Forms\Components\Textarea::make('description')
                                            ->required()
                                            ->columnSpanFull(),
                    ])
                    ->columns(12),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('destinations')
                                            ->required()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('facade_url')
                                            ->required()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('directions')
                                            ->required()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('amenities')
                                            ->required()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('key_location')
                                            ->required()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('percent_down_payment')
                                            ->required()
                                            ->numeric()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('down_payment_term')
                                            ->required()
                                            ->numeric()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('percent_miscellaneous_fees')
                                            ->required()
                                            ->numeric()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('percent_gross_monthly_income')
                                            ->required()
                                            ->numeric()
                                            ->columnSpan(4),
                        Forms\Components\Textarea::make('digital_assets')
                                            ->required()
                                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('balance_payment_interest_rate')
                                            ->required()
                                            ->numeric()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('max_age')
                                            ->required()
                                            ->numeric()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('mortgage_redemption_insurance_fee')
                                            ->required()
                                            ->numeric()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('maximum_paying_age')
                                            ->required()
                                            ->numeric()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('income_requirement_multiplier')
                                            ->required()
                                            ->numeric()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('processing_fee')
                                            ->required()
                                            ->numeric()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('project_code')
                                            ->required()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('property_type')
                                            ->required()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('house_type')
                                            ->required()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('unit_type')
                                            ->required()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('balance_payment_term')
                                            ->required()
                                            ->numeric()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('floor_area')
                                            ->required()
                                            ->numeric()
                                            ->columnSpan(4),
                        Forms\Components\TextInput::make('lot_area')
                                            ->required()
                                            ->columnSpan(4),
                        Forms\Components\Toggle::make('phased_out')
                                            ->required()
                                            ->columnSpan(4),
                    ])
                    ->columns(12),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at','desc')
            ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('sku')
                    ->searchable()
                    ->label('SKU'),
                TextColumn::make('project_code')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->project_code', 'like', "%{$search}%");
                    })
                    ->label('Project Code'),
                TextColumn::make('name')
                    ->searchable()
                    ->label('Name'),
                TextColumn::make('brand')
                    ->searchable()
                    ->label('Brand'),
                TextColumn::make('category')
                    ->searchable()
                    ->label('Category'),
                TextColumn::make('description')
                    ->searchable()
                    ->label('Description'),
                TextColumn::make('price')
                    ->searchable()
                    ->label('Price'),
                TextColumn::make('phased_out')
                    ->badge()
                    ->label('Phased Out')
                    ->getStateUsing(fn($record) => ($record->phased_out) ? 'Yes' : 'No')
                    ->color(fn (string $state) => match ($state) {
                        'No' => 'success',
                        default => 'danger'
                    }),
                TextColumn::make('processing_fee')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->processing_fee', 'like', "%{$search}%");
                    })
                    ->label('Processing Fee'),
                TextColumn::make('destinations')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->destinations', 'like', "%{$search}%");
                    })
                    ->label('Destinations'),
                TextColumn::make('directions')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->directions', 'like', "%{$search}%");
                    })
                    ->label('Directions'),
                TextColumn::make('amenities')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->amenities', 'like', "%{$search}%");
                    })
                    ->label('Amenities'),
                TextColumn::make('facade_url')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->facade_url', 'like', "%{$search}%");
                    })
                    ->label('Facade URL'),
                TextColumn::make('lot_area')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->lot_area', 'like', "%{$search}%");
                    })
                    ->label('Lot Area'),
                TextColumn::make('floor_area')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->floor_area', 'like', "%{$search}%");
                    })
                    ->label('Floor Area'),
                TextColumn::make('property_type')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->property_type', 'like', "%{$search}%");
                    })
                    ->label('Property Type'),



                TextColumn::make('house_type')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->property_type', 'like', "%{$search}%");
                    })
                    ->label('House Type'),
                TextColumn::make('unit_type')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->unit_type', 'like', "%{$search}%");
                    })
                    ->label('Unit Type'),
                TextColumn::make('appraised_value')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->appraised_value', 'like', "%{$search}%");
                    })
                    ->label('Appraised Value'),
                TextColumn::make('percent_down_payment')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->percent_down_payment', 'like', "%{$search}%");
                    })
                    ->label('Percent Down Payment'),
                TextColumn::make('down_payment_term')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->down_payment_term', 'like', "%{$search}%");
                    })
                    ->label('Down Payment Term'),
                TextColumn::make('balance_payment_term')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->balance_payment_term', 'like', "%{$search}%");
                    })
                    ->label('Balance Payment Term'),
                TextColumn::make('percent_miscellaneous_fees')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->percent_miscellaneous_fees', 'like', "%{$search}%");
                    })
                    ->label('Percent Miscellaneous Fees'),
                TextColumn::make('balance_payment_interest_rate')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->balance_payment_interest_rate', 'like', "%{$search}%");
                    })
                    ->label('Balance Payment Interest Rate'),
                TextColumn::make('max_age')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->max_age', 'like', "%{$search}%");
                    })
                    ->label('Max Age'),
                TextColumn::make('percent_gross_monthly_income')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->percent_gross_monthly_income', 'like', "%{$search}%");
                    })
                    ->label('Percent GMI'),
                TextColumn::make('mortgage_redemption_insurance_fee')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->mortgage_redemption_insurance_fee', 'like', "%{$search}%");
                    })
                    ->label('MRIF'),
                TextColumn::make('income_requirement_multiplier')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->income_requirement_multiplier', 'like', "%{$search}%");
                    })
                    ->label('Income Requirement Multiplier'),
                TextColumn::make('maximum_paying_age')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->maximum_paying_age', 'like', "%{$search}%");
                    })
                    ->label('Maximum Paying Age'),
                TextColumn::make('key_location')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->key_location', 'like', "%{$search}%");
                    })
                    ->label('Key Location'),
                TextColumn::make('digital_assets')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->digital_assets', 'like', "%{$search}%");
                    })
                    ->label('Digital Assets'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data ,Product $record) {
                        $data['price']=$record->price->getAmount()->toFloat();

                        $data['destinations'] = $record->meta->destinations;
                        $data['facade_url'] = $record->meta->facade_url;
                        $data['directions'] = $record->meta->directions;
                        $data['amenities'] = $record->meta->amenities;
                        $data['key_location'] = $record->meta->key_location;
                        $data['percent_down_payment'] = $record->meta->percent_down_payment;
                        $data['down_payment_term'] = $record->meta->down_payment_term;
                        $data['percent_miscellaneous_fees'] = $record->meta->percent_miscellaneous_fees;
                        $data['digital_assets'] = $record->meta->digital_assets;
                        $data['percent_gross_monthly_income'] = $record->meta->percent_gross_monthly_income;
                        $data['balance_payment_interest_rate'] = $record->meta->balance_payment_interest_rate;
                        $data['max_age'] = $record->meta->max_age;
                        $data['mortgage_redemption_insurance_fee'] = $record->meta->mortgage_redemption_insurance_fee;
                        $data['maximum_paying_age'] = $record->meta->maximum_paying_age;
                        $data['income_requirement_multiplier'] = $record->meta->income_requirement_multiplier;
                        $data['processing_fee'] = $record->meta->processing_fee;
                        $data['project_code'] = $record->meta->project_code;
                        $data['property_type'] = $record->meta->property_type;
                        $data['house_type'] = $record->meta->house_type;
                        $data['unit_type'] = $record->meta->unit_type;
                        $data['balance_payment_term'] = $record->meta->balance_payment_term;
                        $data['floor_area'] = $record->meta->floor_area;
                        $data['lot_area'] = $record->meta->lot_area;
                        $data['phased_out'] = $record->meta->phased_out;
                        return $data;
                    })
                    ->using(function (Model $record, array $data): Model {
                        $record->update($data);

                        $record->destinations = $data['destinations'];
                        $record->facade_url = $data['facade_url'];
                        $record->directions = $data['directions'];
                        $record->amenities = $data['amenities'];
                        $record->key_location = $data['key_location'];
                        $record->percent_down_payment = $data['percent_down_payment'];
                        $record->down_payment_term = $data['down_payment_term'];
                        $record->percent_miscellaneous_fees = $data['percent_miscellaneous_fees'];
                        $record->digital_assets = $data['digital_assets'];
                        $record->percent_gross_monthly_income = $data['percent_gross_monthly_income'];
                        $record->balance_payment_interest_rate = $data['balance_payment_interest_rate'];
                        $record->max_age = $data['max_age'];
                        $record->mortgage_redemption_insurance_fee = $data['mortgage_redemption_insurance_fee'];
                        $record->maximum_paying_age = $data['maximum_paying_age'];
                        $record->income_requirement_multiplier = $data['income_requirement_multiplier'];
                        $record->processing_fee = $data['processing_fee'];
                        $record->project_code = $data['project_code'];
                        $record->property_type = $data['property_type'];
                        $record->house_type = $data['house_type'];
                        $record->unit_type = $data['unit_type'];
                        $record->balance_payment_term = $data['balance_payment_term'];
                        $record->floor_area = $data['floor_area'];
                        $record->lot_area = $data['lot_area'];
                        $record->phased_out = $data['phased_out'];

                        $record->save();

                        return $record;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProducts::route('/'),
        ];
    }
}


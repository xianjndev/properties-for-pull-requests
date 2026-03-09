<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductsResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
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
                        Forms\Components\TextInput::make('sku'),
                        Forms\Components\TextInput::make('name'),
                        Forms\Components\TextInput::make('brand'),
                        Forms\Components\TextInput::make('category'),
                        Forms\Components\TextInput::make('description'),
                        Forms\Components\TextInput::make('price'),
                        Forms\Components\TextInput::make('destinations'),
                        Forms\Components\TextInput::make('directions'),
                        Forms\Components\TextInput::make('amenities'),
                        Forms\Components\TextInput::make('facade_url'),
                        Forms\Components\TextInput::make('lot_area'),
                        Forms\Components\TextInput::make('floor_area'),
                        Forms\Components\TextInput::make('property_type'),
                        Forms\Components\TextInput::make('house_type'),
                        Forms\Components\TextInput::make('unit_type'),
                        Forms\Components\TextInput::make('appraised_value'),
                        Forms\Components\TextInput::make('percent_down_payment'),
                        Forms\Components\TextInput::make('balance_payment_term'),
                        Forms\Components\TextInput::make('down_payment_term'),
                        Forms\Components\TextInput::make('percent_miscellaneous_fees'),
                        Forms\Components\TextInput::make('balance_payment_interest_rate'),
                        Forms\Components\TextInput::make('percent_gross_monthly_income'),
                        Forms\Components\TextInput::make('max_age'),
                        Forms\Components\TextInput::make('mortgage_redemption_insurance_fee'),
                        Forms\Components\TextInput::make('income_requirement_multiplier'),
                        Forms\Components\TextInput::make('maximum_paying_age'),
                        Forms\Components\TextInput::make('key_location'),
                        Forms\Components\TextInput::make('digital_assets'),
                        Forms\Components\Toggle::make('phased_out'),
                        Forms\Components\Livewire::make(
                            'nested-comments::comments',
                            fn (?Model $record) => [
                                'record' => $record,
                            ]
                        )->hiddenOn('create')
                            ->key(fn (?Model $record) => 'comments-' . $record?->getKey())
                            ->columnSpanFull(),
                        Forms\Components\Livewire::make('update-logs-table')
                            ->key(fn ($get, $record) => 'update-logs-' . $record->getKey())
                            ->columnSpanFull(),
                    ])
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
                        $data['brand']=$record->brand;
                        //                        $data['market_segment']=$record->product->market_segment;
                        $data['price']=$record->price->getAmount()->toFloat();

                        $data['floor_area']=$record->meta->floor_area;
                        $data['lot_area']=$record->meta->lot_area;
                        $data['unit_type']=$record->meta->unit_type;
                        $data['facade_url']=$record->meta->facade_url;

                        $data['percent_dp']=$record->meta->percent_dp;
                        $data['percent_mf']=$record->meta->percent_mf;
                        $data['dp_term']=$record->meta->dp_term;
                        $data['percent_down_payment']=$record->meta->percent_down_payment;
                        $data['down_payment_term']=$record->meta->down_payment_term;
                        $data['balance_payment_term']=$record->meta->balance_payment_term;
                        $data['percent_miscellaneous_fees']=$record->meta->percent_miscellaneous_fees;
                        $data['balance_payment_interest_rate']=$record->meta->balance_payment_interest_rate;

                        $data['destinations']=$record->destinations;
                        $data['amenities']=$record->amenities;
                        $data['key_location']=$record->key_location;
                        $data['digital_assets']=$record->digital_assets;
                        $data['phased_out']=$record->phased_out;
                        return $data;
                    })
                    ->using(function (Model $record, array $data): Model {
                        $record->update($data);
                        $record->brand = $data['brand'];

                        $record->price=$data['price'];
                        $record->category = $data['category'];
                        $record->floor_area = $data['floor_area'];
                        $record->lot_area = $data['lot_area'];
                        $record->unit_type = $data['unit_type'];
                        $record->facade_url = $data['facade_url'];

                        $record->meta->set('percent_dp',$data['percent_dp']);
                        $record->meta->set('percent_mf',$data['percent_mf']);
                        $record->meta->set('dp_term',$data['dp_term']);
                        $record->destinations = $data['destinations'];
                        $record->amenities = $data['amenities'];
                        $record->key_location = $data['key_location'];
                        $record->digital_assets = $data['digital_assets'];
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


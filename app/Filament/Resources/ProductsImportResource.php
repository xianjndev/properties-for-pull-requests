<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductsImportResource\Pages;
use App\Filament\Resources\ProductsImportResource\RelationManagers;
use App\Models\Status;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Homeful\Products\Models\Product;
use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class ProductsImportResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Products Old';
    protected static ?string $label = 'Products Old';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Code')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->label('Property Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('brand')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('category')
                            ->maxLength(255),
//                        Forms\Components\TextInput::make('description')
//                            ->maxLength(255),
                        Forms\Components\TextInput::make('price')
                            ->label('Price')
                            ->numeric(),
                        Forms\Components\TextInput::make('tcp')
                            ->label('TCP')
                            ->numeric(),
                        Forms\Components\TextInput::make('facade_url')
                            ->label('Facade URL')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('percent_dp')
                            ->label('Down Payment Percentage')
                            ->numeric(),
                        Forms\Components\TextInput::make('dp_term')
                            ->label('Down Payment Term')
                            ->numeric(),
                        Forms\Components\TextInput::make('percent_mf')
                            ->label('Miscellaneous Fees Percentage')
                            ->numeric(),
                        Forms\Components\TextInput::make('code')
                            ->label('Property Code')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('type')
                            ->label('Property Type')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('cluster')
                            ->label('Cluster')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phase')
                            ->label('Phase')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('block')
                            ->label('Block')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('lot')
                            ->label('Lot')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('floor_area')
                            ->label('Floor Area (sqm)')
                            ->numeric(),
                        Forms\Components\TextInput::make('lot_area')
                            ->label('Lot Area (sqm)')
                            ->numeric(),

                        Forms\Components\TextInput::make('bedrooms')
                            ->numeric(),
                         Forms\Components\TextInput::make('toilets_and_bathrooms')
                             ->numeric(),
                        Forms\Components\TextInput::make('parking_slots')
                            ->numeric(),
                        Forms\Components\TextInput::make('carports')
                            ->numeric(),

                        Forms\Components\TextInput::make('unit_type')
                            ->label('Unit Type')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('project_code')
                            ->label('Project Code')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('project_location')
                            ->label('Project Location')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('project_address')
                            ->label('Project Address')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tcp')
                            ->label('Total Contract Price (TCP)')
                            ->numeric(),
                        Forms\Components\TextInput::make('unit_type_interior')
                            ->label('Unit Type Interior')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('status_code')
                            ->label('Status Code'),
                        Forms\Components\TextInput::make('key_location')
                            ->label('Location'),
                        Forms\Components\TextInput::make('destinations')
                            ->label('Destination'),
                        Forms\Components\TextInput::make('amenities')
                            ->label('Amenities'),
                        Forms\Components\Textarea::make('project_description')
                            ->label('Project Description')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('digital_assets')
                            ->label('Digital Assets')
                            ->columnSpanFull(),



                    ])
                    ->columnSpan(2)->columns(2),
                    Forms\Components\Section::make()
                    ->schema([
                        Placeholder::make('created_at')
                            ->content(fn ($record) => $record?->created_at?->diffForHumans() ?? new HtmlString('&mdash;')),

                        Placeholder::make('updated_at')
                            ->content(fn ($record) => $record?->created_at?->diffForHumans() ?? new HtmlString('&mdash;'))

                    ])->columnSpan(1),


            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(50)
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('project_location')
                    ->label('Project Location')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->project_location', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->project_location', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('project_code')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->project_code', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->project_code', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->label('Property Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phase')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->phase', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->phase', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('block')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->block', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->block', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('lot')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->lot', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->lot', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('lot_area')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->lot_area', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->lot_area', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('floor_area')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->floor_area', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->floor_area', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('bedrooms')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->bedrooms', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->bedrooms', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('toilets_and_bathrooms')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->toilets_and_bathrooms', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->toilets_and_bathrooms', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('parking_slots')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->parking_slots', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->parking_slots', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('carports')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->carports', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->carports', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('project_address')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->project_address', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->project_address', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('type')
                    ->label('Property Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_type')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->unit_type', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->unit_type', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('unit_type_interior')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->unit_type_interior', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->unit_type_interior', 'like', "%{$search}%");
                    }),
//                Tables\Columns\TextColumn::make('consultation_fee')
//                    ->label('Processing Fee')
//                    ->numeric()
//                    ->sortable(),
                Tables\Columns\TextColumn::make('product.brand')
                    ->label('Brand'),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.price')
                    ->label('Price')
                    ->money('PHP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tcp')
                    ->label('TCP')
                    ->money('PHP')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->tcp', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->tcp', 'like', "%{$search}%");
                    }),
//                Tables\Columns\TextColumn::make('product.market_segment')
//                    ->label('Market Segment')
//                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->getStateUsing(fn ($record) =>
                        trim(($record->product->market_segment ?? '') . ' ' . ($record->product->brand ?? ''))
                    ),
                Tables\Columns\TextColumn::make('appraised_value')
                    ->numeric()
                    ->sortable(),


                Tables\Columns\TextColumn::make('status_code')
                    ->getStateUsing(fn($record) =>$record->product->status_code??'')
                    ->label('Status Code')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->status_code', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->status_code', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('key_location')
                    ->getStateUsing(fn($record) =>$record->product->key_location??'')
                    ->label('Key Location')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->location', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->location', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('destinations')
                    ->getStateUsing(fn($record) =>$record->product->destinations??'')
                    ->label('Desctination')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->destinations', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->destinations', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('amenities')
                    ->getStateUsing(fn($record) =>$record->product->amenities??'')
                    ->label('Amenities')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->amenities', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->amenities', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('facade_url')
                    ->getStateUsing(fn($record) =>$record->product->facade_url??'')
                    ->label('Facade URL')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->facade_url', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->facade_url', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('project_description')
                    ->getStateUsing(fn($record) =>$record->project->project_description??'')
                    ->label('Project Description')
                    ->words(10),
                Tables\Columns\TextColumn::make('digital_assets')
                    ->getStateUsing(fn($record) =>$record->product->digital_assets??'')
                    ->label('Digital Assets')
                    ->limit(70),



                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\ViewAction::make()
//                    ->mutateRecordDataUsing(function (array $data, Model $record): array {
//                        $data['brand']=$record->product->brand;
//                        $data['market_segment']=$record->product->market_segment;
//                        $data['price']=$record->product->price->getAmount()->toFloat();
//                        $data['category']=$record->product->market_segment.' '.$record->product->brand;
//                        return $data;
//                    }),
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data, Model $record): array {
                        $data['brand']=$record->product->brand;
//                        $data['market_segment']=$record->product->market_segment;
                        $data['price']=$record->product->price->getAmount()->toFloat();
                        $data['tcp']=$record->tcp;
                        $data['category']=$record->product->market_segment.' '.$record->product->brand;
                        $data['unit_type_interior']=$record->unit_type_interior;
                        $data['phase']=$record->phase;
                        $data['block']=$record->block;
                        $data['lot']=$record->lot;
                        $data['floor_area']=$record->floor_area;
                        $data['lot_area']=$record->lot_area;
                        $data['unit_type']=$record->unit_type;
                        $data['project_code']=$record->project_code;
                        $data['project_location']=$record->project_location;
                        $data['project_address']=$record->project_address;
                        $data['facade_url']=$record->product->facade_url;

                        $data['percent_dp']=$record->product->meta->percent_dp;
                        $data['percent_mf']=$record->product->meta->percent_mf;
                        $data['dp_term']=$record->product->meta->dp_term;

                        $data['bedrooms']=$record->bedrooms;
                        $data['toilets_and_bathrooms']=$record->toilets_and_bathrooms;
                        $data['parking_slots']=$record->parking_slots;
                        $data['carports']=$record->carports;
                        $data['status_code']=$record->product->status_code;
                        $data['destinations']=$record->product->destinations;
                        $data['amenities']=$record->product->amenities;
                        $data['key_location']=$record->product->key_location;
                        $data['project_description']=$record->project->project_description;
                        $data['digital_assets']=$record->product->digital_assets;

                        return $data;
                    })
                    ->using(function (Model $record, array $data): Model {
                        $record->update($data);
                        $record->product->brand = $data['brand'];
//                        $record->product->market_segment = $data['market_segment'];
                        $record->product->price=$data['price'];
                        $record->tcp = $data['tcp'];
                        $record->product->category = $data['category'];
                        $record->unit_type_interior = $data['unit_type_interior'];
                        $record->phase = $data['phase'];
                        $record->block = $data['block'];
                        $record->lot = $data['lot'];
                        $record->floor_area = $data['floor_area'];
                        $record->lot_area = $data['lot_area'];
                        $record->unit_type = $data['unit_type'];
                        $record->project_code = $data['project_code'];
                        $record->project_location = $data['project_location'];
                        $record->project_address = $data['project_address'];
                        $record->project->project_description = $data['project_description'];
                        $record->product->facade_url = $data['facade_url'];

                        $record->product->meta->set('percent_dp',$data['percent_dp']);
                        $record->product->meta->set('percent_mf',$data['percent_mf']);
                        $record->product->meta->set('dp_term',$data['dp_term']);
                        $record->product->status_code = $data['status_code'];
                        $record->product->destinations = $data['destinations'];
                        $record->product->amenities = $data['amenities'];
                        $record->product->key_location = $data['key_location'];
                        $record->product->digital_assets = $data['digital_assets'];

                        $record->project->save();
                        $record->product->save();

                        $record->bedrooms=(integer) ($data['bedrooms'] ?? 0);
                        $record->toilets_and_bathrooms=(integer) ($data['toilets_and_bathrooms'] ?? 0);
                        $record->parking_slots=(integer) ($data['parking_slots'] ?? 0);
                        $record->carports=(integer) ($data['carports'] ?? 0);
//                        dd($record->product);

//                        dd(
//                            [$record->product->percent_dp, $record->product->percent_mf, $record->product->dp_term],
//                            [$data['percent_dp'],$data['percent_mf'],$data['dp_term'],$data['dp_term']]
//                        );
                        $record->save();

                        return $record;
                    })
                    ->modalWidth(MaxWidth::SevenExtraLarge),
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('update_status')
                    ->label('Update Status')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->native(false)
                            ->options(
                                Status::pluck('description','code')
                            )
                            ->searchable()
                            ->required()
                    ])
                    ->action(function ($record, array $data){
                        $record->update([
                            'status'=>$data['status']
                        ]);
                        $record->save();
                    })
                    ->modalWidth(MaxWidth::Small)
            ],Tables\Enums\ActionsPosition::BeforeCells)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProductsImports::route('/'),
        ];
    }
}

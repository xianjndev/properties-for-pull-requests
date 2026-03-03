<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Models\Status;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Homeful\Properties\Models\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

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
                            ->label('SKU')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('project_code')
                            ->label('Project Code')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->label('Property Name')
                            ->required()
                            ->maxLength(255),

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
                        Forms\Components\TextInput::make('building')
                            ->label('Building')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('floor_area')
                            ->label('Floor Area (sqm)')
                            ->numeric(),
                        Forms\Components\TextInput::make('lot_area')
                            ->label('Lot Area (sqm)')
                            ->numeric(),
                        Forms\Components\TextInput::make('unit_type')
                            ->label('Unit Type')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('unit_type_interior')
                            ->label('Unit Type Interior')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('house_color')
                            ->label('House Color')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('roof_style')
                            ->label('Roof Style')
                            ->maxLength(255),
                        Forms\Components\Checkbox::make('end_unit')
                            ->label('End Unit'),
                        Forms\Components\Checkbox::make('veranda')
                            ->label('Veranda'),
                        Forms\Components\Checkbox::make('balcony')
                            ->label('Balcony'),
                        Forms\Components\Checkbox::make('firewall')
                            ->label('Firewall'),
                        Forms\Components\Checkbox::make('eaves')
                            ->label('Eaves'),
                        Forms\Components\TextInput::make('bedrooms')
                            ->numeric(),
                        Forms\Components\TextInput::make('toilets_and_bathrooms')
                            ->numeric(),
                        Forms\Components\TextInput::make('parking_slots')
                            ->numeric(),
                        Forms\Components\TextInput::make('carports')
                            ->numeric(),
                        Forms\Components\TextInput::make('project_code')
                            ->label('Project Code')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tcp')
                            ->label('Total Contract Price (TCP)')
                            ->numeric(),

                        Forms\Components\TextInput::make('status_code')
                            ->label('Status Code'),


                    ])
                    ->columnSpan(2)->columns(2),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->content(fn ($record) => $record?->created_at?->diffForHumans() ?? new HtmlString('&mdash;')),

                        Forms\Components\Placeholder::make('updated_at')
                            ->content(fn ($record) => $record?->created_at?->diffForHumans() ?? new HtmlString('&mdash;'))

                    ])->columnSpan(1),
                Forms\Components\Livewire::make('update-logs-table')
                    ->key(fn ($get, $record) => 'update-logs-' . $record->getKey())
                    ->columnSpanFull(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at','desc')
            ->defaultPaginationPageOption(50)
            ->columns([
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('project_code')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->project_code', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->project_code', 'like', "%{$search}%");
                    }),
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
                Tables\Columns\TextColumn::make('cluster')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->cluster', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->cluster', 'like', "%{$search}%");
                    }),
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
                Tables\Columns\TextColumn::make('building')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->building', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->building', 'like', "%{$search}%");
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
                Tables\Columns\TextColumn::make('house_color')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->house_color', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->house_color', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('roof_style')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->roof_style', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->roof_style', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('end_unit')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->end_unit', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->end_unit', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('veranda')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->veranda', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->veranda', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('balcony')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->balcony', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->balcony', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('firewall')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->firewall', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->firewall', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('eaves')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->eaves', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->eaves', 'like', "%{$search}%");
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
                Tables\Columns\TextColumn::make('project_code')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('meta->project_code', $direction);
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('meta->project_code', 'like', "%{$search}%");
                    }),



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
                    ->mutateRecordDataUsing(function (array $data, Property $record): array {

                        $data['code']=$record->code;
                        $data['name']=$record->name;
                        $data['type']=$record->type;
                        $data['cluster']=$record->cluster;
                        $data['phase']=$record->phase;
                        $data['block']=$record->block;
                        $data['lot']=$record->lot;
                        $data['building']=$record->building;
                        $data['floor_area']=$record->floor_area;
                        $data['lot_area']=$record->lot_area;
                        $data['unit_type']=$record->unit_type;
                        $data['unit_type_interior']=$record->unit_type_interior;
                        $data['house_color']=$record->house_color;
                        $data['roof_style']=$record->roof_style;
                        $data['end_unit']=$record->end_unit;
                        $data['veranda']=$record->veranda;
                        $data['balcony']=$record->balcony;
                        $data['firewall']=$record->firewall;
                        $data['eaves']=$record->eaves;
                        $data['bedrooms']=$record->bedrooms;
                        $data['toilets_and_bathrooms']=$record->toilets_and_bathrooms;
                        $data['parking_slots']=$record->parking_slots;
                        $data['carports']=$record->carports;
                        $data['project_code']=$record->project_code;
                        $data['sku']=$record->sku;
                        $data['tcp']=$record->tcp;
                        $data['status_code']=$record->product->status_code;

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
                Tables\Actions\Action::make('update_status')
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
            'index' => Pages\ManageProperties::route('/'),
        ];
    }
}

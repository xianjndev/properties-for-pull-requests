<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Models\PropertyStatusLog;
use App\Models\Status;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Homeful\Properties\Models\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
                            ->columnSpan(3)
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->columnSpan(6)
                            ->label('Property Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code')
                            ->columnSpan(3)
                            ->unique(ignoreRecord: true)
                            ->label('Property Code')
                            ->required(),
                        Forms\Components\TextInput::make('type')
                            ->columnSpan(3)
                            ->label('Property Type')
                            ->required(),
                        Forms\Components\TextInput::make('cluster')
                            ->columnSpan(2)
                            ->label('Cluster')
                            ->required(),
                        Forms\Components\TextInput::make('status')
                            ->columnSpan(3)
                            ->label('Status')
                            ->required(),
                            
                        Forms\Components\TextInput::make('tcp')
                            ->columnSpan(4)
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('unit_type_interior')
                            ->columnSpan(2)
                            ->required(),
                        Forms\Components\TextInput::make('phase')
                            ->columnSpan(2)
                            ->required(),
                        Forms\Components\TextInput::make('block')
                            ->columnSpan(2)
                            ->required(),
                        Forms\Components\TextInput::make('lot')
                            ->columnSpan(2)
                            ->required(),
                        Forms\Components\TextInput::make('floor_area')
                            ->columnSpan(2)
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('lot_area')
                            ->columnSpan(2)
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('unit_type')
                            ->columnSpan(4)
                            ->required(),
                        Forms\Components\TextInput::make('project_code')
                            ->columnSpan(4)
                            ->required(),
                        Forms\Components\TextInput::make('project_location')
                            ->columnSpan(4)
                            ->required(),
                        Forms\Components\TextInput::make('project_address')
                            ->columnSpan(6)
                            ->required(),
                        Forms\Components\TextInput::make('bathrooms')
                            ->columnSpan(2)
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('toilets_and_bathrooms')
                            ->columnSpan(2)
                            ->numeric() 
                            ->required(),
                        Forms\Components\TextInput::make('parking_slots')
                            ->columnSpan(2)
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('carports')
                            ->columnSpan(2)
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('project_description')
                            ->columnSpan(10)
                            ->required(),
                        Forms\Components\Textarea::make('digital_assets')
                            ->columnSpanFull()
                            ->required(),


                    ])
                    ->columnSpan(10)->columns(12),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->content(fn ($record) => $record?->created_at?->diffForHumans() ?? new HtmlString('&mdash;')),

                        Forms\Components\Placeholder::make('updated_at')
                            ->content(fn ($record) => $record?->created_at?->diffForHumans() ?? new HtmlString('&mdash;'))

                    ])->columnSpan(1),
                Forms\Components\Livewire::make(
                        'nested-comments::comments',
                        fn (?Model $record) => [
                            'record' => $record,
                        ]
                    )->hiddenOn('create')
                    ->key(fn (?Model $record) => 'comments-' . $record?->getKey())
                    ->columnSpanFull(),
                Forms\Components\Livewire::make('status-log-table')
                    ->key(fn ($get, $record) => 'status-logs-' . $record->getKey())
                    ->columnSpanFull(),
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

                        $data['tcp'] = $record->meta->get('tcp');
                        $data['unit_type_interior'] = $record->meta->get('unit_type_interior');
                        $data['phase'] = $record->meta->get('phase');
                        $data['block'] = $record->meta->get('block');
                        $data['lot'] = $record->meta->get('lot');
                        $data['floor_area'] = $record->meta->get('floor_area');
                        $data['lot_area'] = $record->meta->get('lot_area');
                        $data['unit_type'] = $record->meta->get('unit_type');
                        $data['project_code'] = $record->meta->get('project_code');
                        $data['project_location'] = $record->meta->get('project_location');
                        $data['project_address'] = $record->meta->get('project_address');
                        $data['bathrooms'] = $record->meta->get('bathrooms');
                        $data['toilets_and_bathrooms'] = $record->meta->get('toilets_and_bathrooms');
                        $data['parking_slots'] = $record->meta->get('parking_slots');
                        $data['carports'] = $record->meta->get('carports');
                        $data['project_description'] = $record->meta->get('project_description');
                        $data['digital_assets'] = $record->meta->get('digital_assets');

                        return $data;
                    })
                    ->using(function ($record, array $data) {
                        $record->update($data);

                        $record->meta->set('tcp', $data['tcp']);
                        $record->meta->set('unit_type_interior', $data['unit_type_interior']);
                        $record->meta->set('phase', $data['phase']);
                        $record->meta->set('block', $data['block']);
                        $record->meta->set('lot', $data['lot']);
                        $record->meta->set('floor_area', $data['floor_area']);
                        $record->meta->set('lot_area', $data['lot_area']);
                        $record->meta->set('unit_type', $data['unit_type']);
                        $record->meta->set('project_code', $data['project_code']);
                        $record->meta->set('project_location', $data['project_location']);
                        $record->meta->set('project_address', $data['project_address']);
                        $record->meta->set('bathrooms', $data['bathrooms']);
                        $record->meta->set('toilets_and_bathrooms', $data['toilets_and_bathrooms']);
                        $record->meta->set('parking_slots', $data['parking_slots']);
                        $record->meta->set('carports', $data['carports']);
                        $record->meta->set('project_description', $data['project_description']);
                        $record->meta->set('digital_assets', $data['digital_assets']);

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
                            ->required(),
                        TextArea::make('remarks')
                            ->label('Remarks')
                            ->cols(10)
                            ->rows(5)
                            ->maxLength(255),
                    ])
                    ->action(function ($record, array $data){

                        $record->update([
                            'status'=>$data['status']
                        ]);

                        PropertyStatusLog::created([
                            'property_code'=>$record->code,
                            'status_code'=>$data['status'],
                            'status_description'=>Status::where('code',$data['status'])->first()->description??'',
                            'user_id'=>auth()->id(),
                            'remarks'=>$data['remarks'],
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

<?php

namespace App\Livewire;

use App\Models\PropertyStatusLog;
use App\Models\UpdateLog;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
class StatusLogTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    public $record = null;
    public function mount($record)
    {
        // Filament will pass the current record here
        $this->record = $record;
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at','desc')
            ->query(
                PropertyStatusLog::query()
                    ->where('property_code', $this->record->code)
            )
            ->columns([
                TextColumn::make('updated_at')
                    ->formatStateUsing(function (string $state, Model $record) {
                        $date = Carbon::parse($state);
                        $formattedDate = $date->format('F j, Y');
                        $formattedTime = $date->format('g:i A');
                        $timeAgo = $date->diffForHumans(); // 1 hour ago
                        $user = $record->user==null?'':$record->user->name;
                        return $formattedDate . '<br>' . $formattedTime . '<br>'.$user. '<br><small>' . $timeAgo . '</small>';
                    })
                    ->html()
                    ->wrap()
                    ->grow(false),
                TextColumn::make('status_description')
                    ->label('Status')
                    ->wrap(),
                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->wrap(),

            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.status-log-table');
    }
}

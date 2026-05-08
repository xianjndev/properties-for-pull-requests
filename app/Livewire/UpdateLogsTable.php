<?php

namespace App\Livewire;

use App\Models\SysDevTasksUpdateLogs;
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

class UpdateLogsTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    protected static ?string $heading = 'Update Logs';

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
                UpdateLog::query()
                    ->where('loggable_type', get_class($this->record))
                    ->where('loggable_id', $this->record->id)
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
                TextColumn::make('field'),
                TextColumn::make('from')->wrap(),
                TextColumn::make('to')->wrap(),
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
        return view('livewire.update-logs-table');
    }
}

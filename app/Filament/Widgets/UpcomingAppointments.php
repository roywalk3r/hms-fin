<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class UpcomingAppointments extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
protected static ?string $pollingInterval = null;
    protected function getTableQuery(): Builder
    {
        return Appointment::query()
			->whereDate('date', '>=', now())
            ->orderBy('date')
            ->limit(5);
    }
	   public function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(5)
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns());
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('patient.name')
                ->label('Patient')
                ->searchable(),
            Tables\Columns\TextColumn::make('staff.name')
                ->label('Doctor')
                ->searchable(),
            Tables\Columns\TextColumn::make('date')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('reason')
                ->limit(30),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'primary' => 'scheduled',
                    'success' => 'confirmed',
                    'danger' => 'cancelled',
                ]),
        ];
    }
}


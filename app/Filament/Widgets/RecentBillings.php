<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BillingResource;
use App\Models\Billing;
use Filament\Resources\Components\Tab;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentBillings extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;
protected static ?string $pollingInterval = null;
    protected function getTableQuery(): Builder
    {
        return Billing::query()
            ->latest()
            ->limit(5);
    }
    public function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(5)
            ->query($this->getTableQuery())
            ->actions($this->getTableActions())
            ->columns($this->getTableColumns());
    }
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('patient.name')
                ->label('Patient')
                ->searchable(),
            Tables\Columns\TextColumn::make('total_amount')
                ->money('usd', true)
                ->sortable(),
            Tables\Columns\TextColumn::make('paid_amount')
                ->money('ghs', true)
                ->sortable(),
            Tables\Columns\TextColumn::make('due_date')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->colors([
                    'success' => 'paid',
                    'warning' => 'partial',
                    'danger' => 'pending',
                ]),
        ];
    }

    protected function getTableActions(): array
    {
        return [

            Tables\Actions\EditAction::make()
                ->url(fn (Billing $record): string => BillingResource::getUrl('edit', ['record'=> $record]))
                ->openUrlInNewTab(),
        ];
    }
}

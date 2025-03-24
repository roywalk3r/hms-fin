<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BillingRelationManager extends RelationManager
{
    protected static string $relationship = 'billing';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('patient_id')
                    ->default(fn ($record) => $record?->patient?->id),
                Forms\Components\Select::make('appointment_id')
                    ->label('Appointment')
                    ->options(fn () => \App\Models\Appointment::with('patient', 'staff')
                        ->get()
                        ->mapWithKeys(fn ($appointment) => [
                            $appointment->id => "{$appointment->patient->name} - Dr. {$appointment->staff->name} ({$appointment->date->format('d M Y, h:i A')})"
                        ])
                    )
            ->preload()
            ->searchable()
            ->native(false)
            ->required(),
                Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('paid_amount')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('due_date')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('usd', true),
                Tables\Columns\TextColumn::make('paid_amount')
                    ->money('usd', true),
                Tables\Columns\TextColumn::make('due_date')
                    ->date(),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                    ]),
                ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}

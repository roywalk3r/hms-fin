<?php

namespace App\Filament\Resources\AppointmentResource\RelationManagers;

use App\Filament\Resources\BillingResource;
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('patient_id')
                    ->relationship('patient', 'name')
                    ->searchable()
                    ->preload()
                         ->default(fn ($record) => $record?->appointment?->patient_id)
                         ->createOptionForm([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\DatePicker::make('date_of_birth')->required(),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('address')->required(),
                        Forms\Components\TextInput::make('phone' )->tel()->required(),
                        Forms\Components\TextInput::make('email')->email()->required(),
                        Forms\Components\TextInput::make('emergency_contact')->required(),
                    ])->columns(3)
                    ->required(),
                Forms\Components\TextInput::make('total_amount')
                    ->numeric()
                    ->prefix('₵')
                    ->required(),
                Forms\Components\TextInput::make('paid_amount')
                    ->numeric()
                    ->prefix('₵')
                    ->default(0),
                Forms\Components\DatePicker::make('due_date')
                    ->native(false)
                    ->required(),
                Forms\Components\ToggleButtons::make('status')
                    ->inline()
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                    ])
                    ->colors([
                        'paid' => 'success',
                        'overdue' => 'danger',
                        'pending' => 'warning',
                    ])
                    ->icons([
                        'pending' => 'heroicon-o-pencil',
                        'overdue' => 'heroicon-o-clock',
                        'paid' => 'heroicon-o-check-circle',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return BillingResource::table($table)
            ->filters([
                //
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

<?php

namespace App\Filament\Resources;

use App\Filament\RelationManagers\AuditLogRelationalManager;
use App\Filament\Resources\BillingResource\Pages;
use App\Filament\Resources\BillingResource\RelationManagers;
use App\Models\Billing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class BillingResource extends Resource
{
    protected static ?string $model = Billing::class;

    protected static ?string $recordTitleAttribute = 'patient.name';


    public static function canViewAny(): bool
    {
        return Auth::user()->can('view billing');
    }
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('patient_id')
                    ->relationship('patient', 'name')
                    ->searchable()
                     ->preload()
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('total_amount')->money('ghc', true)->sortable(),
                Tables\Columns\TextColumn::make('paid_amount')->money('ghc', true)->sortable(),
                Tables\Columns\TextColumn::make('due_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('status')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                    ]),
                ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            AuditLogRelationalManager::class
        ];
    }
    public static function getGloballySearchableAttributes(): array
{
    return ['patient.name'];
}
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBillings::route('/'),
            'create' => Pages\CreateBilling::route('/create'),
            'edit' => Pages\EditBilling::route('/{record}/edit'),
        ];
    }
}

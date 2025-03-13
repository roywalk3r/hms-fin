<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Billing;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Random\RandomException;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';


    /**
     * @throws RandomException
     */
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('invoice_number')
                    ->default('INV-' . random_int(100000, 999999))
                    ->unique()
                    ->required(),
                Forms\Components\Select::make('patient_id')
                    ->relationship('patient', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('appointment_id')
                    ->label('Appointment')
                    ->options(fn () => \App\Models\Appointment::with('patient', 'staff')
                        ->get()
                        ->mapWithKeys(fn ($appointment) => [
                            $appointment->id => "{$appointment->patient->name} - Dr. {$appointment->staff->name} ({$appointment->date->format('d M Y, h:i A')})"
                        ])
                    )
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->live()
                    ->afterStateUpdated(fn ($state, $set) =>
                    $set('total_amount', Billing::where('appointment_id', $state)->value('total_amount') ?? 0)
                    ),

                Forms\Components\TextInput::make('total_amount')
                    ->numeric()
                    ->live()
                    ->reactive()
                    ->required(),
                Forms\Components\ToggleButtons::make('status')
                    ->inline()
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ])
                    ->colors([
                        'paid' => 'success',
                        'cancelled' => 'danger',
                        'pending' => 'warning',
                    ])
                    ->icons([
                        'pending' => 'heroicon-o-pencil',
                        'cancelled' => 'heroicon-o-x-circle',
                        'paid' => 'heroicon-o-check-circle',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\DateTimePicker::make('due_date')->native(false)->default(Carbon::now()),
                Forms\Components\TextInput::make('payment_method'),
                Forms\Components\Textarea::make('notes'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')->sortable()->searchable(),
                TextColumn::make('patient.name')->label('Patient'),
                TextColumn::make('appointment.staff.name')->label('Doctor'),
                TextColumn::make('appointment.date')->label('Appointment Date')->dateTime(),                TextColumn::make('total_amount')->sortable(),
                TextColumn::make('status')->sortable(),
                TextColumn::make('due_date')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->url(fn (Invoice $record) => route('invoice.download', $record), true)
                    ->openUrlInNewTab(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}

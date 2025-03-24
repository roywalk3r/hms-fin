<?php

namespace App\Filament\Resources\AppointmentResource\RelationManagers;

use App\Filament\Resources\MedicalRecordResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'records';

//    public function form(Form $form): Form
//    {
//        return MedicalRecordResource::form($form);
//    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('appointment_id')
                    ->default(fn ($record) => $record?->id), // Automatically set appointment_id
                Forms\Components\Select::make('patient_id')
                    ->relationship('patient', 'name')
                    ->preload()
                    ->searchable()
                    ->default(fn ($record) => $record?->patient_id) // Auto-select patient
                    ->required(),
                Forms\Components\DatePicker::make('date')->required()->native(false),
                Forms\Components\Textarea::make('diagnosis')->required(),
                Forms\Components\Textarea::make('treatment')->required(),
                Forms\Components\Textarea::make('notes'),
            ]);
    }


    public function table(Table $table): Table
    {
        return MedicalRecordResource::table($table)
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

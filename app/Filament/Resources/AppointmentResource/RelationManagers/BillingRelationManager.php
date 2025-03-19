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
        return BillingResource::form($form);
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

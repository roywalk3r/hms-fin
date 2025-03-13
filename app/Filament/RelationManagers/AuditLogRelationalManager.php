<?php

namespace App\Filament\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuditLogRelationalManager extends RelationManager
{
    protected static string $relationship = 'auditLog';

    protected static ?string $label = "Audit Log";
    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('event')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('auditable_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('auditable_id')
                    ->required(),
                Forms\Components\KeyValue::make('old_values')
                    ->label('Old Values')
                    ->columnSpan('full'),
                Forms\Components\KeyValue::make('new_values')
                    ->label('New Values')
                    ->columnSpan('full'),
                Forms\Components\TextInput::make('ip_address')
                    ->maxLength(255)
                    ->disabled(),
                Forms\Components\Textarea::make('user_agent')
                    ->maxLength(1000)
                    ->disabled()
                    ->columnSpan('full'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Audit Log')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('event')
                    ->label('Event')
                    ->sortable()
                    ->searchable(),
//                Tables\Columns\TextColumn::make('auditable_type')
//                    ->label('Auditable Type')
//                    ->sortable()
//                    ->searchable(),
//                Tables\Columns\TextColumn::make('auditable_id')
//                    ->label('Auditable ID')
//                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->sortable()
                    ->dateTime('d M Y H:i'),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->sortable(),
            ])        ->defaultSort('created_at', 'desc') ->persistSortInSession()
            ->filters([
                // Add filters if needed
            ])
            ->headerActions([
                // Removed create action as audit logs should not be manually created
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Removed delete bulk action as audit logs should not be deletable
            ]);
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\RelationManagers\AuditLogRelationalManager;
use App\Filament\Resources\AuditLogResource\Pages;
use App\Models\AuditLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;


class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    public static function canViewAny(): bool
    {
        return Auth::user()->can('delete departments');
    }
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('action')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('loggable_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('loggable_id')
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event')
                    ->label('Event')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('auditable_type')
                    ->label('Auditable Type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('auditable_id')
                    ->label('Auditable ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_type')
                    ->label('User Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('User ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->sortable()
                    ->dateTime('d M Y H:i'),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('event')
                    ->label('Event')
                    ->query(fn (Builder $query): Builder => $query->where('event', 'like', 'created%')),
                Tables\Filters\Filter::make('auditable_type')
                    ->label('Auditable Type')
                    ->query(fn (Builder $query, $data): Builder => $query->where('auditable_type', $data)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AuditLogRelationalManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditLogs::route('/'),
            'create' => Pages\CreateAuditLog::route('/create'),
        ];
    }
}

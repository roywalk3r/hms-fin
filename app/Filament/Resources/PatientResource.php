<?php

namespace App\Filament\Resources;

use App\Filament\RelationManagers\AuditLogRelationalManager;
use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use App\Services\CacheService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;
     protected static ?string $navigationIcon = 'heroicon-o-user';
protected static ?string $recordTitleAttribute = 'name';
    public static function canViewAny(): bool
    {
        return Auth::user()->can('edit patients');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Forms\Components\TextInput::make('phone')->tel()->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('emergency_contact')->required(),
                ])->columns(2);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')->date()->sortable(),
                Tables\Columns\TextColumn::make('gender')->sortable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('email'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
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
            AuditLogRelationalManager::class,
            RelationManagers\AppointmentsRelationManager::class,
            RelationManagers\MedicalRecordsRelationManager::class,
            RelationManagers\BillingRelationManager::class,
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return static::getCachedPatients();
    }

protected static function getCachedPatients(): Builder
{
    $cacheKey = 'patients_list';
    $cacheTtl = 3600; // 1 hour

    // Measure time to get cached data or query from the database
    $start = microtime(true);

    $patientIds = CacheService::remember($cacheKey, $cacheTtl, function () {
        $queryStart = microtime(true);
        
        // Fetch patient IDs from the database
        $ids = Patient::pluck('id')->toArray();
        
        $queryEnd = microtime(true);
        Log::info('Database query time for IDs: ' . ($queryEnd - $queryStart) . ' seconds');
        
        return $ids;
    });

    $end = microtime(true);
    Log::info('Total time to get patient IDs (including potential caching): ' . ($end - $start) . ' seconds');

    // Return the query for patients with the cached IDs
    return Patient::whereIn('id', $patientIds);
}

    public static function getGloballySearchableAttributes(): array
{
    return ['name', 'email',  'phone'];
}
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }

}

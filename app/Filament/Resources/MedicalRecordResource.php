<?php

namespace App\Filament\Resources;

use App\Filament\RelationManagers\AuditLogRelationalManager;
use App\Filament\Resources\MedicalRecordResource\Pages;
use App\Filament\Resources\MedicalRecordResource\RelationManagers;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Services\CacheService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MedicalRecordResource extends Resource
{
    protected static ?string $model = MedicalRecord::class;
    public static function canViewAny(): bool
    {
        return Auth::user()->can('view patients');
    }
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $recordTitleAttribute = 'name';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('patient_id')
                    ->relationship('patient', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('date')->required(),
                Forms\Components\Textarea::make('diagnosis')->required(),
                Forms\Components\Textarea::make('treatment')->required(),
                Forms\Components\Textarea::make('notes'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('date')->date()->sortable(),
                Tables\Columns\TextColumn::make('diagnosis')->limit(50),
                Tables\Columns\TextColumn::make('treatment')->limit(50),
            ])
            ->filters([
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'],
                                fn($query, $date) => $query->whereDate('date', '>=', $date)
                            )
                            ->when($data['created_until'],
                                fn($query, $date) => $query->whereDate('date', '<=', $date)
                            );
                    }),
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
  public static function getEloquentQuery(): Builder
   {
       return static::getCachedRecords();
   }
    protected static function getCachedRecords(): Builder
   {
       $cacheKey = 'medical_records';
        $cacheTtl = 3600; // 1 hour

       $start = microtime(true);
       $medirec = CacheService::remember($cacheKey, $cacheTtl, function () {
            $queryStart = microtime(true);
         $ids = Patient::pluck('id')->toArray();
           $queryEnd = microtime(true);
           Log::info('Database query time for IDs: ' . ($queryEnd - $queryStart) . ' seconds');
           return $ids;
        });
       $end = microtime(true);

       Log::info('Total time to get record IDs (including potential caching): ' . ($end - $start) . ' seconds');

        return MedicalRecord::whereIn('id', $medirec);
   }
public static function getGloballySearchableAttributes(): array
{
    return ['diagnosis',  'patient.name'];
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedicalRecords::route('/'),
            'create' => Pages\CreateMedicalRecord::route('/create'),
            'edit' => Pages\EditMedicalRecord::route('/{record}/edit'),
        ];
    }
}

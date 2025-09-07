<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers\BillsRelationManager;
use App\Models\Patient;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Hospital Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('email')->email()->required()->maxLength(255),
                Forms\Components\TextInput::make('phone')->required()->maxLength(20),
                Forms\Components\Textarea::make('medical_history')->maxLength(2000),
                Forms\Components\DatePicker::make('last_visit'),
                Forms\Components\FileUpload::make('files')
                    ->label('Patient Files')
                    ->multiple()
                    ->directory('patients')
                    ->preserveFilenames()
                    ->enableDownload()
                    ->getUploadedFileNameForStorageUsing(fn ($file) => now()->timestamp . '_' . $file->getClientOriginalName())
                    ->storeFileNamesIn('patient_files'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('phone')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('last_visit')->date()->sortable(),
                Tables\Columns\TextColumn::make('bills_count')
                    ->counts('bills')
                    ->label('Bills'),
            ])
            
            ->filters([
                Tables\Filters\Filter::make('recent')
                    ->query(fn ($query) => $query->where('last_visit', '>=', now()->subMonth())),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BillsRelationManager::class,
        ];
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
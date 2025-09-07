<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use App\Models\Bill;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;
use App\Filament\Resources\BillResource\Pages;

class BillsRelationManager extends RelationManager
{
    protected static string $relationship = 'bills';
    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('patient_id')
                    ->relationship('patient', 'name')
                    ->disabled(),
                Forms\Components\TextInput::make('description')->required()->maxLength(500),
                Forms\Components\TextInput::make('amount')->numeric()->required(),
                Forms\Components\DatePicker::make('bill_date')->required(),
                Forms\Components\Select::make('payment_status')
                    ->options([
                        'Pending' => 'Pending',
                        'Paid' => 'Paid',
                        'Partially Paid' => 'Partially Paid',
                    ])
                    ->default('Pending')
                    ->required(),
                Forms\Components\Repeater::make('inventories')
                    ->relationship('inventories')
                    ->schema([
                        Forms\Components\TextInput::make('inventory_id')->disabled(),
                        Forms\Components\TextInput::make('quantity')->numeric()->required(),
                        Forms\Components\TextInput::make('cost')->numeric()->required(),
                    ])
                    ->columns(3)
                    ->disableLabel(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Bill ID')->sortable(),
                Tables\Columns\TextColumn::make('amount')->money('usd')->sortable(),
                Tables\Columns\TextColumn::make('bill_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('payment_status')->sortable(),
                Tables\Columns\TextColumn::make('description')->limit(50)->wrap(),
                Tables\Columns\TextColumn::make('inventories_count')
                    ->counts('inventories')
                    ->label('Items'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'Pending' => 'Pending',
                        'Paid' => 'Paid',
                        'Partially Paid' => 'Partially Paid',
                    ]),
                Tables\Filters\Filter::make('recent')
                    ->query(fn ($query) => $query->where('bill_date', '>=', now()->subMonth())),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
               ->label('View Bill')
              ->icon('heroicon-o-eye')
               ->url(fn (Bill $record) => Pages\ViewBill::getUrl(['record' => $record]))
    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
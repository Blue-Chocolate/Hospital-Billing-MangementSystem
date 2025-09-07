<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillResource\Pages;
use App\Models\Bill;
use App\Models\Inventory;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BillResource extends Resource
{
    protected static ?string $model = Bill::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('patient_id')
                    ->relationship('patient', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('description'),

                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required(),

                Forms\Components\DatePicker::make('bill_date')
                    ->required(),

                Forms\Components\Select::make('payment_status')
                    ->options([
                        'Pending' => 'Pending',
                        'Paid' => 'Paid',
                        'Partially Paid' => 'Partially Paid',
                    ])
                    ->default('Pending'),

                Forms\Components\Repeater::make('inventories')
                    ->relationship('inventories')
                    ->schema([
                        Forms\Components\Select::make('inventory_id')
                            ->label('Inventory')
                            ->relationship('inventories', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('quantity')->numeric()->required(),
                        Forms\Components\TextInput::make('cost')->numeric()->required(),
                    ])
                    ->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.name')->label('Patient')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('amount')->money('usd')->sortable(),
                Tables\Columns\TextColumn::make('bill_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('payment_status')->sortable(),
                Tables\Columns\TextColumn::make('description')->limit(30),

                Tables\Columns\TextColumn::make('inventories_count')
                    ->counts('inventories')
                    ->label('Inventory Items'),

                Tables\Columns\TextColumn::make('inventories.name')->label('Items')->wrap(),
                Tables\Columns\TextColumn::make('inventories.pivot.quantity')->label('Qty'),
                Tables\Columns\TextColumn::make('inventories.pivot.cost')->label('Cost')->money('usd'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'Pending' => 'Pending',
                        'Paid' => 'Paid',
                        'Partially Paid' => 'Partially Paid',
                    ]),

                Tables\Filters\SelectFilter::make('patient')
                    ->relationship('patient', 'name'),

                Tables\Filters\Filter::make('recent')
                    ->label('Recent Bills (1 Month)')
                    ->query(fn($query) => $query->where('bill_date', '>=', now()->subMonth())),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBills::route('/'),
            'create' => Pages\CreateBill::route('/create'),
            'edit' => Pages\EditBill::route('/{record}/edit'),
        'view' => Pages\ViewBill::route('/{record}'), // ✅ register view page
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillResource\Pages;
use App\Models\Bill;
use App\Services\BillAnomalyService;
use App\Services\BillExportService;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BillResource extends Resource
{
    protected static ?string $model = Bill::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    /** Payment status options */
    public const PAYMENT_STATUS_PENDING = 'Pending';
    public const PAYMENT_STATUS_PAID = 'Paid';
    public const PAYMENT_STATUS_PARTIALLY_PAID = 'Partially Paid';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema(static::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([])
            ->actions(static::getTableActions())
            ->bulkActions(static::getTableBulkActions());
    }

    /** ---------------- FORM / TABLE SCHEMAS ---------------- */

    protected static function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('patient_id')
                ->relationship('patient', 'name')
                ->required(),

            Forms\Components\Select::make('department_id')
                ->relationship('department', 'name')
                ->required(),

            Forms\Components\TextInput::make('amount')
                ->numeric()
                ->required()
                ->minValue(0)
                ->maxValue(99999.99),

            Forms\Components\DatePicker::make('bill_date')
                ->required(),

            Forms\Components\Textarea::make('description'),

            Forms\Components\TextInput::make('insurance_provider'),

            Forms\Components\TextInput::make('insurance_coverage')
                ->numeric()
                ->default(0)
                ->minValue(0)
                ->maxValue(99999.99),

            Forms\Components\Select::make('payment_status')
                ->options([
                    self::PAYMENT_STATUS_PENDING => self::PAYMENT_STATUS_PENDING,
                    self::PAYMENT_STATUS_PAID => self::PAYMENT_STATUS_PAID,
                    self::PAYMENT_STATUS_PARTIALLY_PAID => self::PAYMENT_STATUS_PARTIALLY_PAID,
                ])
                ->default(self::PAYMENT_STATUS_PENDING),
        ];
    }

    protected static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('patient.name')->searchable(),
            Tables\Columns\TextColumn::make('department.name')->searchable(),
            Tables\Columns\TextColumn::make('amount')
                ->formatStateUsing(fn ($state) => number_format($state, 2))
                ->searchable(),
            Tables\Columns\TextColumn::make('bill_date')->date()->searchable(),
            Tables\Columns\TextColumn::make('insurance_provider')->searchable(),
            Tables\Columns\TextColumn::make('insurance_coverage')
                ->formatStateUsing(fn ($state) => number_format($state, 2))
                ->searchable(),
            Tables\Columns\TextColumn::make('payment_status')->searchable(),
            Tables\Columns\BooleanColumn::make('is_anomaly'),
        ];
    }

    protected static function getTableActions(): array
    {
        return [
            Tables\Actions\EditAction::make(),
            Tables\Actions\Action::make('print_receipt')
                ->url(fn (Bill $record): string => route('bill.receipt', $record))
                ->openUrlInNewTab(),
            Tables\Actions\Action::make('check_anomaly')
                ->action(fn (Bill $record, BillAnomalyService $service) => $service->checkAndNotify($record))
                ->requiresConfirmation(),
        ];
    }

    protected static function getTableBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('export_monthly')
                    ->label('Export Monthly Report')
                    ->action(fn (BillExportService $service) => $service->exportMonthlyReport())
                    ->requiresConfirmation(),
            ]),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBills::route('/'),
            'create' => Pages\CreateBill::route('/create'),
            'edit' => Pages\EditBill::route('/{record}/edit'),
        ];
    }
}

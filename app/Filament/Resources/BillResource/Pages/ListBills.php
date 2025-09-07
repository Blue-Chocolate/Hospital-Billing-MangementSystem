<?php

namespace App\Filament\Resources\BillResource\Pages;
use Filament\FontProviders\GoogleFontProvider;

use App\Filament\Resources\BillResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListBills extends ListRecords
{
    protected static string $resource = BillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Generate a bill')
        ];
    }
}

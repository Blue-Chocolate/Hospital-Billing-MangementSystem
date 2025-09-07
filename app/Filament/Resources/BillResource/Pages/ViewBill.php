<?php

namespace App\Filament\Resources\BillResource\Pages;

use App\Filament\Resources\BillResource;
use Filament\Resources\Pages\Page;
use App\Models\Bill;

class ViewBill extends Page
{
    // Link this page to the BillResource
    protected static string $resource = BillResource::class;

    // Specify the Blade view
    protected static string $view = 'filament.resources.bills.view-bill';

    // The bill record to display
    public Bill $bill;

    /**
     * Mount the page with a Bill record
     *
     * @param Bill $record
     */
    public function mount(Bill $record): void
    {
        $this->bill = $record;
    }

    /**
     * Optional: set page title
     */
    // protected function getTitle(): string
    // {
    //     return "Bill #" . $this->bill->id;
    // }
}

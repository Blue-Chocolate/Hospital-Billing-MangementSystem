<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class Topbar extends Widget
{
    protected static string $view = 'filament.widgets.topbar';

    // Optional: set full width

    public function getResources(): array
    {
        // Add your Filament resources here
        return [
        [
            'label' => 'Products',
            'url' => url('/admin/products'),
        ],
        [
            'label' => 'Orders',
            'url' => url('/admin/orders'),
        ],
        [
            'label' => 'Customers',
            'url' => url('/admin/customers'),
        ],

        
    ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Inventory extends Model
{
    use Notifiable; // Include this trait if using Laravel notifications

    protected $fillable = ['name', 'quantity', 'unit_price', 'low_stock_threshold'];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:8,2',
        'low_stock_threshold' => 'integer',
    ];

    public function notifyLowStock()
    {
        if ($this->quantity <= $this->low_stock_threshold) {
            $this->notify(new \App\Notifications\LowStockNotification($this));
        }
    }
}

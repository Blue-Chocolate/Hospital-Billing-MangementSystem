<?php

namespace App\Notifications;

use App\Models\Inventory;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LowStockNotification extends Notification
{
    use Queueable;

    protected $inventory;

    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public function via($notifiable)
    {
        return ['mail']; // Or ['database', 'broadcast'] depending on your needs
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Low Stock Alert')
            ->line("The inventory item '{$this->inventory->name}' has a quantity of {$this->inventory->quantity}, which is below the threshold of {$this->inventory->low_stock_threshold}.")
            ->action('View Inventory', url('/admin/inventories/' . $this->inventory->id . '/edit'))
            ->line('Please restock as soon as possible.');
    }

    public function toArray($notifiable)
    {
        return [
            'inventory_id' => $this->inventory->id,
            'name' => $this->inventory->name,
            'quantity' => $this->inventory->quantity,
            'threshold' => $this->inventory->low_stock_threshold,
            'message' => 'Low stock alert',
        ];
    }
}
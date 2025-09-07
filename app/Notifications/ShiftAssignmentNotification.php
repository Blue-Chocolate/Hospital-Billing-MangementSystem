<?php

namespace App\Notifications;

use App\Models\Shift;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ShiftAssignmentNotification extends Notification
{
    use Queueable;

    protected $shift;

    public function __construct(Shift $shift)
    {
        $this->shift = $shift;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Shift Assignment')
            ->line("You have been assigned a shift.")
            ->line("Start: {$this->shift->start_time}")
            ->line("End: {$this->shift->end_time}")
            ->action('View Shifts', url('/admin/shifts'));
    }
}

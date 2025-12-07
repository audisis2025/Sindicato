<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProcedureReminderNotification extends Notification
{
    use Queueable;

    public string $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * Notification channels.
     */
    public function via($notifiable): array
    {
        return ['database']; // se guarda en la tabla notifications
    }

    /**
     * Data that will be stored in the database.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'title'   => 'Recordatorio de trámite',
            'message' => $this->message,
            'type'    => 'reminder',
        ];
    }
}

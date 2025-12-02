<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Ticket;

class NewTicketNotification extends Notification
{
    use Queueable;

    public $ticket;

    /**
     * Create a new notification instance.
     *
     * @param Ticket $ticket
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // We use 'database' for in-app notifications and 'mail' for email
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Ticket Created: #' . $this->ticket->id)
            ->line('A new ticket has been created and requires your attention.')
            ->action('View Ticket', url('/tickets/' . $this->ticket->id))
            ->line('Ticket Name: ' . $this->ticket->ticket_name);
    }

    /**
     * Get the array representation of the notification for the database channel.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => 'New Ticket Assigned',
            'message' => 'Ticket #' . $this->ticket->id . ' has been created and is waiting for review.',
            'status' => 'new'
        ];
    }
}

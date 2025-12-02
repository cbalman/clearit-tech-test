<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Ticket;

class DocumentRequestedNotification extends Notification
{
    use Queueable;

    public $ticket;
    public $agentName;

    /**
     * Create a new notification instance.
     *
     * @param Ticket $ticket
     * @return void
     */
    public function __construct(Ticket $ticket, $agentName)
    {
        $this->ticket = $ticket;
        $this->agentName = $agentName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
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
            ->subject('Action Required: Documents Requested for Ticket #' . $this->ticket->id)
            ->line('Agent ' . $this->agentName . ' has reviewed your ticket and is requesting additional documentation.')
            ->line('Please upload the necessary files to proceed with the clearance process.')
            ->action('Upload Documents', url('/tickets/' . $this->ticket->id))
            ->line('Thank you for your cooperation.');
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
            'title' => 'Documents Requested',
            'message' => 'Agent ' . $this->agentName . ' has requested documents for Ticket #' . $this->ticket->id . '.',
            'status' => 'in_progress'
        ];
    }
}

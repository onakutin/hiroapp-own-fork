<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactUser extends Notification
{
    use Queueable;

    protected $to;
    protected $from;
    protected $text;
    protected $subject;

    public function __construct(string $from, string $to, string $text, string $subject)
    {
        $this->from = $from;
        $this->to = $to;
        $this->text = $text;
        $this->subject = $subject;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("You have a new message from {$this->from}: {$this->subject}")
            ->line($this->text);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'from' => $this->from,
            'to' => $this->to,
            'subject' => $this->subject,
            'text' => $this->text,
        ];
    }
}

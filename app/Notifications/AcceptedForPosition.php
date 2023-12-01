<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AcceptedForPosition extends Notification
{
    use Queueable;

    protected $recruiter;
    protected $position;
    protected $subject = "We have good news for you!";

    public function __construct(?object $recruiter, ?string $position)
    {
        $this->recruiter = $recruiter;
        $this->position = $position;
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
            ->subject("Hired for $this->position")
            ->line("Dear $notifiable->first_name,  You have been hired as a $this->position.")
            ->salutation("Regards, {$this->recruiter->first_name} {$this->recruiter->last_name}");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'text' => "Dear {$notifiable->first_name}, \n You have been hired as a {$this->position}. \n Regards, {$this->recruiter->first_name} {$this->recruiter->last_name}",
            'from' => $this->recruiter->email,
            'subject' => $this->subject
        ];
    }
}

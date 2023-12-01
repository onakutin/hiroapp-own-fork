<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationEnded extends Notification
{
    use Queueable;

    protected $recruiter;
    protected $position;
    protected $subject = "We have updates for you";
    /**
     * Create a new notification instance.
     */
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

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Application Ended')
            ->line("Dear {$notifiable->first_name}, We are sorry to inform you that for the position of {$this->position}, we decided to continue with other candidates whose profile match our requirements. Wishing you the best.")
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
            'text' => "Dear {$notifiable->first_name}, \n We are sorry to inform you that for the position of {$this->position}, we decided to continue with other candidates whose profile match our requirements. \n Wishing you the best. \n Regards, {$this->recruiter->first_name} {$this->recruiter->last_name}",
            'from' => $this->recruiter->email,
            'subject' => $this->subject
        ];
    }
}

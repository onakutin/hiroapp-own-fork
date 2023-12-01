<?php

namespace App\Notifications;

use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterviewInvitation extends Notification
{
    use Queueable;

    protected $text;
    protected $datetime;
    protected $place;
    protected $sender;
    protected $subject = 'Your Interview Details...';

    public function __construct(?string $text, ?string $datetime, ?string $place, ?array $sender)
    {
        $this->text = $text;
        $this->datetime = $datetime;
        $this->place = $place;
        $this->sender = $sender;
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

    public function convertDate($dateTimeString): string
    {
        $dateTimeObject = new DateTime($dateTimeString);
        return $dateTimeObject->format('Y-m-d H:i');
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Invitation to Interview')
            ->line($this->text)
            ->line($this->convertDate($this->datetime))
            ->line($this->place)
            ->salutation("Regards, {$this->sender['first_name']} {$this->sender['last_name']}");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'datetime' => $this->datetime,
            'place' => $this->place,
            'text' => "{$this->text} \n when: {$this->convertDate($this->datetime)} \n where: {$this->place} \n Regards, {$this->sender['first_name']} {$this->sender['last_name']}",
            'from' => $this->sender['email'],
            'subject' => $this->subject
        ];
    }
}

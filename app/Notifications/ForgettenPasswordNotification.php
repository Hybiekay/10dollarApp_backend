<?php

namespace App\Notifications;

use Ichtrojan\Otp\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgettenPasswordNotification extends Notification
{
    use Queueable;

    public $message;
    public $subject;
    public $fromEmail;
    public $mailer;
    private $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->message ="Use This Otp To verify you email ";
        $this->subject = "Forgetten Password Otp";
        $this->fromEmail= "admin@10dollar-app.com";
        $this->mailer= "smtp";
        $this->otp = new Otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $otp1 = $this->otp->generate($notifiable->email,'numeric', 6, 60);
        return (new MailMessage)
                    ->subject($this->subject)
                    ->mailer($this->mailer)
                    ->greeting("Hello ".$notifiable->user_name)
                    ->line($this->message)
                    ->line('Code: '.$otp1->token);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

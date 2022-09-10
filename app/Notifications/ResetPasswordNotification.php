<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use VRoute;

class ResetPasswordNotification extends Notification
{
    use Queueable;
    private $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {   
    	$url = VRoute::get('reset-password').'?token='.$this->token.'&email='.$notifiable->getEmailForPasswordReset();
        return (new MailMessage)
        	->subject(request()->getHost().' đặt lại mật khẩu')
        	->view('mail_templates.reset_password', ['url' => $url]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

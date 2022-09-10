<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\UserNotification;

class User extends Notification
{
    use Queueable;

    public $data;
    public $type;
    public $user; 

    public function __construct($data,$type,$user)
    {
        $this->data = $data;
        $this->type = $type;
        $this->user = $user;
    }
   
    public function via($notifiable)
    {
        return [UserNotification::class];
    }

    public function toDatabase($notifiable){
        return $this->data;
    }
}

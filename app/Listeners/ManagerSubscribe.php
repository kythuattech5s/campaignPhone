<?php

namespace App\Listeners;

class ManagerSubscribe
{
    public function subscribe($events)
    {
        $events->listen('notification.user', function($data,$type,$user){
            $this->sendNotificationUser($data,$type,$user);
        });
    }

    public function sendNotificationUser($data,$type,$user){
        $user->sendNotification($data,$type);
    }
}

?>

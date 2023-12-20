<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $notification_id;

    public function __construct($notification_id,$message)
    {
        $this->notification_id = $notification_id;
        $this->message = $message;
    }

    public function broadcastOn() {
        return 'ProjectSync';
    }


    public function broadcastAs() {
        return 'notification-notificationevent';
    }

}

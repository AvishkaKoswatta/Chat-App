<?php

namespace App\Events;

use App\Models\User;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $user;
    public $message;

    public function __construct(User $user, $message)
    {
        $this->user = $user;
        $this->message = $message;
    }


    public function broadcastOn()
    {
        return new PrivateChannel('chat-app');
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;    
use App\Message;

class AlatRusakNotifikasi implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    public $data;
    /**
     * Create a new event instance.
     */
    public function __construct($message) 
    {
        // $this->message = $message;
        $this->message = $message;
        // $user = User::where('id', $this->message->id)->first();
        // $this->message->user = $user;
        // $this->data= $message->toArray();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            // new PrivateChannel('broken-notifikasi'),
            new Channel('broken-notifikasi'),
        ];
    }
}

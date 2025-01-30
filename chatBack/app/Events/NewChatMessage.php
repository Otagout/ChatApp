<?php

namespace App\Events;

use \Log;
use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

 class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
   
    /**
     * Create a new event instance.
     */

     public $chatMessage;
     public function __construct(ChatMessage $chatMessage)
     {
         $this->chatMessage = $chatMessage;
         \Log::info('Event fired:', ['message' => $chatMessage]);
     }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('chat.' . $this->chatMessage->chat_room_id),
        ];
    }
    public function broadcastWith()
{
    return [
        'id' => $this->chatMessage->id,
        'user_id' => $this->chatMessage->user_id,
        'chat_room_id' => $this->chatMessage->chat_room_id,
        'message' => $this->chatMessage->message,
        'created_at' => $this->chatMessage->created_at->toDateTimeString(),
        'user' => [
            'id' => $this->chatMessage->user->id,
            'name' => $this->chatMessage->user->name,
        ],
    ];
}

}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Http\Resources\CartResource;


class CartEvent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(public $cart, public $message, public $color)
    {}

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('cart.' . $this->cart->user_id);
    }

    public function broadcastWith(): array
    {
        return [
            'cart' => new CartResource($this->cart),
            'message' => $this->message,
            'color' => $this->color
        ];
    }

    public function broadcastAs(): string
    {
        return 'cart';
    }
}
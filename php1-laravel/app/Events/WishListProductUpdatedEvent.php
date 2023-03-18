<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WishListProductUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public Product $product, public string $message = '')
    {
        logs()->info(self::class);
        $this->message = 'Product from your wish list was updated';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): array
    {
        logs()->info(self::class . '::broadcastOn');
        $followers = $this->product->followers()->get()->pluck('id');
        logs()->info(json_encode($followers));
        $channels = [];

        foreach($followers as $followerId) {
            $channels[] = new PrivateChannel("App.Models.User.{$followerId}");
        }

        return $channels;
    }
}

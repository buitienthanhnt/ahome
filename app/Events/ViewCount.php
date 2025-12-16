<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ViewCount
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $source;

    protected $logTha;

    /**
     * Create a new event instance.
     *
     * @var array $source
     * @return void
     */
    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}

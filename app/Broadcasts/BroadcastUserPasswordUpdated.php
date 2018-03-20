<?php

namespace CQRS\Broadcasts;

use CQRS\Aggregates\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Ramsey\Uuid\UuidInterface;

class BroadcastUserPasswordUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public $broadcastQueue = 'broadcasts';

    private $id;

    /**
     * UserCreated constructor.
     * @param UuidInterface $uuid
     */
    public function __construct(UuidInterface $uuid)
    {
        $this->id = $uuid;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("users");
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'id'       => $this->id->toString()
        ];
    }
}
<?php

namespace CQRS\Broadcasts;

use CQRS\Aggregates\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Ramsey\Uuid\UuidInterface;

/**
 * Class BroadcastUserCreated
 * @package CQRS\Broadcasts
 */
class BroadcastUserCreated implements ShouldBroadcast
{
    use SerializesModels;

    public $broadcastQueue = 'broadcasts';

    private $id;

    private $name;

    private $email;

    /**
     * UserCreated constructor.
     * @param UuidInterface $id
     * @param string $name
     * @param string $email
     */
    public function __construct(UuidInterface $id, string $name, string $email)
    {
        $this->id = $id;

        $this->name = $name;

        $this->email = $email;
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
            'id'       => $this->id->toString(),
            'name'     => $this->name,
            'email'    => $this->email
        ];
    }
}
<?php

namespace CQRS\Broadcasts;

use CQRS\DomainModels\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class BroadcastUserPasswordUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public $broadcastQueue = 'broadcasts';

    private $user;

    /**
     * UserCreated constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
            'id'       => $this->user->getId(),
            'name'     => $this->user->getName(),
            'email'    => $this->user->getEmail()
        ];
    }
}
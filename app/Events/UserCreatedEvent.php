<?php

namespace CQRS\Events;

use CQRS\DomainModels\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;


class UserCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $user;

    /**
     * Create a new event instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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

    public function getName()
    {
        return $this->user->getName();
    }

    public function getEmail()
    {
        return $this->user->getEmail();
    }

    public function getPassword()
    {
        return $this->user->getPassword();
    }

    public function getAggregateVersion()
    {
        return $this->user->getAggregateVersion();
    }

    public function getRememberToken()
    {
        return $this->user->getRememberToken();
    }
}

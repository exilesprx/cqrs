<?php

namespace CQRS\Listeners;

use CQRS\Aggregates\User;
use CQRS\Events\UserCreatedCommand;

class UserCreateCommandListener
{
    private $aggregate;

    /**
     * Create the event listener.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->aggregate = $user;
    }

    /**
     * Handle the event.
     *
     * @param  UserCreatedCommand $event
     * @return void
     */
    public function handle(UserCreatedCommand $event)
    {
        $this->aggregate->applyNew($event->getName(), $event->getEmail(), $event->getPassword());

        $this->aggregate->save();
    }
}

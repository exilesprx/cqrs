<?php

namespace CQRS\Listeners;

use CQRS\Events\UserCreatedEvent;
use CQRS\User;

class UserCreateEventListener
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserCreatedEvent  $event
     * @return void
     */
    public function handle(UserCreatedEvent $event)
    {
        User::create([
            'aggregate_version' => $event->getAggregateVersion() ? $event->getAggregateVersion() : 1,
            'name' => $event->getName(),
            'email' => $event->getEmail(),
            'password' => $event->getPassword(),
            'remember_token' => $event->getRememberToken()
        ]);
    }
}

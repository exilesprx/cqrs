<?php

namespace CQRS\Listeners;

use CQRS\Events\UserCreatedEvent;
use CQRS\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreateEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
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
        $user = $event->getUser();

        User::create([
            'aggregate_version' => $user->getAggregateVersion() ? $user->getAggregateVersion() : 1,
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'remember_token' => $user->getRememberToken()
        ]);
    }
}

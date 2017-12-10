<?php

namespace CQRS\Listeners;

use CQRS\Events\UserCreatedCommand;
use CQRS\Repositories\UserRepository;

class UserCreateCommandListener
{
    private $repo;

    /**
     * Create the event listener.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * Handle the event.
     *
     * @param  UserCreatedCommand  $event
     * @return void
     */
    public function handle(UserCreatedCommand $event)
    {
        $this->repo->save($event->getAggregateVersion(), [
            'name' => $event->getName(),
            'email' => $event->getEmail(),
            'password' => $event->getPassword(),
            'remember_token' => $event->getRememberToken()
        ]);
    }
}

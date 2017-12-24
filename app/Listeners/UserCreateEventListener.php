<?php

namespace CQRS\Listeners;

use CQRS\Events\UserCreatedEvent;
use CQRS\Repositories\State\UserRepository;

/**
 * Class UserCreateEventListener
 * @package CQRS\Listeners
 */
class UserCreateEventListener
{
    /**
     * @var UserRepository
     */
    private $repo;

    /**
     * Create the event listener.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * Handle the event.
     *
     * @param  UserCreatedEvent  $event
     * @return void
     */
    public function handle(UserCreatedEvent $event)
    {
        $this->repo->save($event->getName(), $event->getEmail(), $event->getPassword());
    }
}

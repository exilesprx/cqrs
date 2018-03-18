<?php

namespace CQRS\Listeners;

use CQRS\Events\UserCreated;
use CQRS\Events\UserPasswordUpdated;
use CQRS\Repositories\Events\UserRepository;

/**
 * Class UserEventSubscriber
 * @package CQRS\Listeners
 */
class UserEventSubscriber extends EventSubscriberRoot
{
    /**
     * @var UserRepository
     */
    private $repo;

    protected $subscribes = [
        UserCreated::class,
        UserPasswordUpdated::class
    ];

    /**
     * EventListener constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * @param UserCreated $event
     * @return void
     */
    public function onUserCreated(UserCreated $event)
    {
        $this->repo->save(UserCreated::class, $event->getAggregateId(), $event->toArray());
    }

    /**
     * @param UserPasswordUpdated $event
     */
    public function onUserPasswordUpdated(UserPasswordUpdated $event)
    {
        $this->repo->save(UserPasswordUpdated::class, $event->getAggregateId(), $event->toArray());
    }
}

<?php

namespace CQRS\Listeners;

use CQRS\Events\UserCreated;
use CQRS\Events\UserPasswordUpdated;
use CQRS\Repositories\Events\UserRepository;
use Illuminate\Events\Dispatcher;

/**
 * Class UserEventSubscriber
 * @package CQRS\Listeners
 */
class UserEventSubscriber
{
    /**
     * @var UserRepository
     */
    private $repo;

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
        $this->repo->save(get_class($event), $event->getAggregateId(), $event->toArray());
    }

    /**
     * @param UserPasswordUpdated $event
     */
    public function onUserPasswordUpdated(UserPasswordUpdated $event)
    {
        $this->repo->save(get_class($event), $event->getAggregateId(), $event->toArray());
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(UserCreated::class, self::class . "@onUserCreated");

        $events->listen(UserPasswordUpdated::class, self::class . "@onUserPasswordUpdated");
    }
}

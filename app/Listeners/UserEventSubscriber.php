<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/29/17
 * Time: 6:09 PM
 */

namespace CQRS\Listeners;


use CQRS\Events\UserCreatedEvent;
use CQRS\Events\UserUpdateEvent;
use CQRS\Repositories\State\UserRepository;
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
     * UserEventSubscriber constructor.
     * @param UserRepository $repo
     */
    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param UserCreatedEvent $event
     */
    public function onCreateEvent(UserCreatedEvent $event)
    {
        $this->repo->save($event->getAggregateId(), $event->getName(), $event->getEmail(), $event->getPassword());
    }

    /**
     * @param UserUpdateEvent $event
     */
    public function onUpdateEvent(UserUpdateEvent $event)
    {
        $this->repo->update(
            $event->getAggregateId(),
            [
                'name' => $event->getName(),
                'password' => $event->getPassword()
            ]
        );
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            UserCreatedEvent::class,
            self::class . '@onCreateEvent'
        );

        $events->listen(
            UserUpdateEvent::class,
            self::class . '@onUpdateEvent'
        );
    }
}
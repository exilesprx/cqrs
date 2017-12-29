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

class UserEventSubscriber
{
    private $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function onCreateEvent(UserCreatedEvent $event)
    {
        $this->repo->save($event->getName(), $event->getEmail(), $event->getPassword());
    }

    public function onUpdateEvent(UserUpdateEvent $event)
    {
        $this->repo->update(
            $event->getId(),
            [
                'name' => $event->getName(),
                'password' => $event->getPassword()
            ]
        );
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            UserCreatedEvent::class,
            'onCreateEvent'
        );

        $events->listen(
            UserUpdateEvent::class,
            'onUpdateEvent'
        );
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 12/23/17
 * Time: 10:17 PM
 */

namespace CQRS\Aggregates;


use CQRS\Events\EventFactory;
use CQRS\Events\UserCreatedEvent;
use CQRS\Repositories\Events\UserRepository as EventStoreRepo;
use Illuminate\Events\Dispatcher;

/**
 * Class User
 * @package CQRS\Aggregates
 */
class User
{
    /**
     * @var EventStoreRepo
     */
    private $repo;

    /**
     * @var EventFactory
     */
    private $factory;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var \CQRS\DomainModels\User
     */
    private $user;

    /**
     * User constructor.
     * @param EventStoreRepo $repository
     * @param EventFactory $factory
     * @param Dispatcher $dispatcher
     * @param \CQRS\DomainModels\User $user
     */
    public function __construct(EventStoreRepo $repository, EventFactory $factory, Dispatcher $dispatcher, \CQRS\DomainModels\User $user)
    {
        $this->repo = $repository;

        $this->factory = $factory;

        $this->dispatcher = $dispatcher;

        $this->user = $user;
    }

    public function initialize(string $name, string $email, string $password, int $id = 0)
    {
        $this->user->initialize($id, $name, $email, $password);
    }

    public function create()
    {
        $event = $this->factory->make(UserCreatedEvent::SHORT_NAME);

        $this->repo->save(
            $event->getShortName(),
            [
                'name' => $this->user->getName(),
                'email' => $this->user->getEmail(),
                'password' => $this->user->getPassword()
            ]
        );

        $event->handle($this->user->getName(), $this->user->getEmail(), $this->user->getPassword());

        $this->dispatcher->dispatch($event);
    }

    public function update(int $id, string $name, string $password)
    {
//        $this->repo->update($id, [
//            'name' => $name,
//            'password' => $password
//        ]);
//
//        $event->handle();
//        $this->dispatcher->dispatch($event);
    }
}
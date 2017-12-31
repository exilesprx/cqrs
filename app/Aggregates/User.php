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
use CQRS\Events\UserUpdateEvent;
use CQRS\Repositories\Events\UserRepository as EventStoreRepo;
use Illuminate\Events\Dispatcher;
use Ramsey\Uuid\UuidInterface;

/**
 * Class User
 * @package CQRS\Aggregates
 */
class User
{
    /**
     * @var
     */
    private $aggregateId;

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

    /**
     * @param UuidInterface $aggregateId
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function initialize(UuidInterface $aggregateId, string $name, string $email, string $password)
    {
        $this->user->initialize($name, $email, $password);

        $this->aggregateId = $aggregateId;
    }

    /**
     *
     */
    public function create()
    {
        $event = $this->factory->make(UserCreatedEvent::SHORT_NAME);

        $this->repo->save(
            $this->getAggregateId(),
            $event->getShortName(),
            [
                'name' => $this->user->getName(),
                'email' => $this->user->getEmail(),
                'password' => $this->user->getPassword()
            ]
        );

        $event->handle($this->getAggregateId(), $this->user->getName(), $this->user->getEmail(), $this->user->getPassword(), $this->aggregateId);

        $this->dispatcher->dispatch($event);
    }

    /**
     * @return mixed
     */
    public function getAggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * @param iterable $payload
     */
    public function update(iterable $payload)
    {
        $event = $this->factory->make(UserUpdateEvent::SHORT_NAME);

        $this->repo->save(
            $this->getAggregateId(),
            $event->getShortName(),
            $payload
        );

        $event->handle(
            $this->getAggregateId(),
            $payload
        );

        $this->dispatcher->dispatch($event);
    }

    /**
     * @param UuidInterface $aggregateId
     * @param iterable $payload
     */
    public function apply(UuidInterface $aggregateId, iterable $payload)
    {
        $this->aggregateId = $aggregateId;

        if($name = array_get($payload, 'name')) {
            $this->user->setName($name);
        }

        if($email = array_get($payload, 'email')) {
            $this->user->setEmail($email);
        }

        if($password = array_get($payload, 'password')) {
            $this->user->setPassword($password);
        }
    }
}
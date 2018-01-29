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
use Illuminate\Bus\Dispatcher;
use Ramsey\Uuid\UuidInterface;
use CQRS\DomainModels\User as UserDomainModel;

/**
 * Class User
 * @package CQRS\Aggregates
 */
class User
{
    /**
     * @var UuidInterface
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
     * @param UserDomainModel $user
     */
    public function __construct(EventStoreRepo $repository, EventFactory $factory, Dispatcher $dispatcher, UserDomainModel $user)
    {
        $this->repo = $repository;

        $this->factory = $factory;

        $this->dispatcher = $dispatcher;

        $this->user = $user;
    }

    /**
     * @param UuidInterface $uuid
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function initialize(UuidInterface $uuid, string $name, string $email, string $password)
    {
        $this->aggregateId = $uuid;

        $this->user->initialize($name, $email, $password);
    }

    /**
     */
    public function create()
    {
        $uuid = $this->getAggregateId();

        $payload = [
            'name' => $this->user->getName(),
            'email' => $this->user->getEmail(),
            'password' => $this->user->getPassword()
        ];

        $event = $this->factory->make(UserCreatedEvent::SHORT_NAME, $uuid, $payload);

        $eventName = $event->getShortName();

        $this->repo->save(
            $uuid,
            $eventName,
            $payload
        );

        $this->dispatcher->dispatch($event);
    }

    /**
     * @param iterable $payload
     */
    public function update(iterable $payload)
    {
        $uuid = $this->getAggregateId();

        $payload = array_merge($this->user->toArray(), $payload);

        $event = $this->factory->make(UserUpdateEvent::SHORT_NAME, $uuid, $payload);

        $this->repo->save(
            $uuid,
            $event->getShortName(),
            $payload
        );

        $this->dispatcher->dispatch($event);
    }

    /**
     * @param UuidInterface $uuid
     * @param iterable $payload
     */
    public function apply(UuidInterface $uuid, iterable $payload)
    {
        if (!$this->aggregateId) {
            $this->aggregateId = $uuid;
        }

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

    /**
     * @return mixed
     */
    public function getAggregateId()
    {
        return $this->aggregateId;
    }
}
<?php

namespace CQRS\Aggregates;

use CQRS\DomainModels\User as UserDomainModel;
use CQRS\Events\EventFactory;
use CQRS\EventStores\UserStore;
use CQRS\Repositories\Events\UserRepository as EventRepo;
use CQRS\Repositories\State\UserRepository as StateRepo;
use Exception;
use Illuminate\Events\Dispatcher;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

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
     * @var StateRepo
     */
    private $stateRepo;

    /**
     * @var EventRepo
     */
    private $eventRepo;

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
     * @param StateRepo $stateRepo
     * @param EventRepo $eventRepo
     * @param EventFactory $factory
     * @param Dispatcher $dispatcher
     * @param UserDomainModel $user
     */
    public function __construct(StateRepo $stateRepo, EventRepo $eventRepo, EventFactory $factory, Dispatcher $dispatcher, UserDomainModel $user)
    {
        $this->stateRepo = $stateRepo;

        $this->eventRepo = $eventRepo;

        $this->factory = $factory;

        $this->dispatcher = $dispatcher;

        $this->user = $user;
    }

    /**
     * @param UuidInterface $uuid
     * @param iterable $payload
     * @throws Exception
     */
    public function initialize(UuidInterface $uuid, iterable $payload)
    {
        $this->replay($uuid);

        $this->apply(
            $uuid,
            $payload
        );
    }

    /**
     * @return UserDomainModel
     */
    public function create()
    {
        // TODO: Domain validation here

        $uuid = $this->getAggregateId();

        $id = $this->stateRepo->save($uuid, $this->user->getName(), $this->user->getEmail(), $this->user->getPassword());

        $this->user->setId($id);

        return $this->user;
    }

    /**
     * @param string $password
     * @return UserDomainModel
     */
    public function updatePassword(string $password)
    {
        $uuid = $this->getAggregateId();

        $id = $this->stateRepo->update(
            $uuid,
            [
                "password" => $password
            ]
        );

        $this->user->setId($id);

        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getAggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * @param UuidInterface $uuid
     * @param iterable $payload
     * @throws \Exception
     */
    protected function apply(UuidInterface $uuid, iterable $payload)
    {
        if(!$this->aggregateId) {
            $this->aggregateId = $uuid;
        }

        if($this->aggregateId && $this->aggregateId != $uuid)
        {
            throw new Exception("Aggregate mismatch");
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
     * This will replay the events on the aggregate.
     * @param UuidInterface $uuid
     * @throws Exception
     */
    protected function replay(UuidInterface $uuid)
    {
        $events = $this->eventRepo->find($uuid);

        $events->each(function(UserStore $event) use ($uuid)
        {
            if ($event->aggregate_id != $uuid)
            {
                return;
            }

            $this->apply(
                Uuid::fromString($event->aggregate_id),
                $event->data
            );
        });
    }

    /**
     * @param UuidInterface $uuid
     */
    protected function restore(UuidInterface $uuid)
    {
//      $this->factory->make($event->name, $this->aggregateId, $event->data);
//
//      $this->dispatcher->dispatch($event);
    }
}
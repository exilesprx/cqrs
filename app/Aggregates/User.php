<?php

namespace CQRS\Aggregates;

use CQRS\DomainModels\User as UserDomainModel;
use CQRS\Events\EventFactory;
use CQRS\Repositories\Events\UserRepository as EventRepo;
use CQRS\Repositories\State\UserRepository as StateRepo;
use Exception;
use Illuminate\Events\Dispatcher;
use Ramsey\Uuid\UuidInterface;

/**
 * Class User
 * @package CQRS\Aggregates
 */
class User extends AggregateRoot
{
    /**
     * @var StateRepo
     */
    private $stateRepo;

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
        parent::__construct($eventRepo);

        $this->stateRepo = $stateRepo;

        $this->factory = $factory;

        $this->dispatcher = $dispatcher;

        $this->user = $user;
    }

    /**
     * @param UuidInterface $uuid
     * @param iterable $payload
     * @throws Exception
     */
    private function initialize(UuidInterface $uuid, iterable $payload)
    {
        $this->replay($uuid);

        $this->apply(
            $uuid,
            $payload
        );
    }

    /**
     * @param UuidInterface $uuid
     * @param string $name
     * @param string $email
     * @param string $password
     * @return UserDomainModel
     * @throws Exception
     */
    public function create(UuidInterface $uuid, string $name, string $email, string $password)
    {
        // TODO: Domain validation here

        $this->apply(
            $uuid,
            [
                'name' => $name,
                'email' => $email,
                'password' => $password
            ]
        );

        $id = $this->stateRepo->save($uuid, $this->user->getName(), $this->user->getEmail(), $this->user->getPassword());

        $this->user->setId($id);

        return $this->user;
    }

    /**
     * @param UuidInterface $uuid
     * @param string $password
     * @return UserDomainModel
     * @throws Exception
     */
    public function updatePassword(UuidInterface $uuid, string $password)
    {
        $payload = [
            'password' => $password
        ];

        $this->replayEvents(
            $uuid,
            $payload
        );

        $id = $this->stateRepo->update(
            $uuid,
            $payload
        );

        $this->user->setId($id);

        return $this->user;
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
}
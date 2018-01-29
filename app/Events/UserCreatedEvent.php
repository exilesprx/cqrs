<?php

namespace CQRS\Events;

use CQRS\Repositories\State\UserRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;


/**
 * Class UserCreatedEvent
 * @package CQRS\Events
 */
class UserCreatedEvent extends Event implements IEvent
{
    /**
     * @var string
     */
    const SHORT_NAME = "user-create";

    /**
     * @var Uuid
     */
    private $aggregateId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @param UuidInterface $aggregateId
     * @param iterable $payload
     */
    public function __construct($aggregateId, iterable $payload)
    {
        $this->aggregateId = $aggregateId;

        $this->name = array_get($payload, 'name');

        $this->email = array_get($payload, 'email');

        $this->password = array_get($payload, 'password');
    }

    /**
     * @param UserRepository $repo
     */
    public function handle(UserRepository $repo)
    {
        $repo->save($this->aggregateId, $this->name, $this->email, $this->password);
    }

    /**
     * @return Uuid
     */
    public function getAggregateId(): Uuid
    {
        return $this->aggregateId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getShortName() : string
    {
        return self::SHORT_NAME;
    }
}
